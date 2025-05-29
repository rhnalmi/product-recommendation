<?php

namespace App\Filament\Resources\MasterPartResource\Pages;

use App\Filament\Resources\MasterPartResource;
use App\Models\MasterPart;
use App\Models\ImportLog; // Model log impor
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Tables\Actions\Action;

use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB; // Untuk transaksi database
use Illuminate\Support\Facades\Log; // Untuk logging error internal
use Illuminate\Support\Facades\Auth; // Untuk mendapatkan user ID
use League\Csv\Reader; // Library untuk membaca CSV
use League\Csv\Statement;

class ListMasterParts extends ListRecords
{
    protected static string $resource = MasterPartResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),

            Actions\Action::make('importMasterParts')
                ->label('Impor Master Part dari CSV')
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    FileUpload::make('attachment')
                        ->label('File CSV')
                        ->required()
                        ->acceptedFileTypes(['text/csv', 'application/csv'])
                        ->maxSize(5120) // Batas ukuran file 5MB (sesuaikan jika perlu)
                        ->disk('local') // Simpan sementara di disk lokal (atau 'public' jika dikonfigurasi)
                        ->directory('csv-imports') // Direktori penyimpanan sementara
                        ->helperText('File CSV harus memiliki header: part_number, part_name, part_price. Pastikan encoding UTF-8.'),
                ])
                ->action(function (array $data) {
                    $this->importCsv($data['attachment']);
                }),
        ];
    }

    public function importCsv(string $filePath): void
    {
        $fullPath = storage_path('app/' . $filePath); // Dapatkan path absolut ke file yang diunggah

        if (!file_exists($fullPath)) {
            Notification::make()
                ->title('File Tidak Ditemukan')
                ->body('File CSV yang diunggah tidak dapat ditemukan di server.')
                ->danger()
                ->send();
            return;
        }

        $fileChecksum = md5_file($fullPath);

        // Cek apakah file dengan checksum ini sudah pernah berhasil diimpor
        $existingLog = ImportLog::where('file_checksum', $fileChecksum)
            ->where('status', 'success')
            ->first();

        if ($existingLog) {
            Notification::make()
                ->title('File Duplikat')
                ->body('File ini sudah pernah berhasil diimpor pada ' . $existingLog->created_at->format('d-m-Y H:i') . '. Proses impor dibatalkan.')
                ->warning()
                ->send();
            
            // Opsional: hapus file yang baru diunggah jika sudah ada duplikat
            // unlink($fullPath); 
            return;
        }
        
        // Buat log awal
        $importLog = ImportLog::create([
            'file_name' => basename($filePath),
            'file_checksum' => $fileChecksum,
            'status' => 'pending',
            'user_id' => Auth::id(),
        ]);

        $newRecordsCount = 0;
        $updatedRecordsCount = 0;
        $processedRowsCount = 0;
        $totalRowsInCsv = 0;

        DB::beginTransaction(); // Mulai transaksi

        try {
            $csv = Reader::createFromPath($fullPath, 'r');
            $csv->setHeaderOffset(0); // Baris pertama adalah header

            $header = $csv->getHeader(); // Dapatkan header
            $requiredHeaders = ['part_number', 'part_name', 'part_price'];

            // Validasi header
            foreach ($requiredHeaders as $requiredHeader) {
                if (!in_array($requiredHeader, $header)) {
                    throw new \Exception("Header CSV tidak valid. Pastikan terdapat kolom: " . implode(', ', $requiredHeaders));
                }
            }
            
            $records = Statement::create()->process($csv);
            $totalRowsInCsv = count($records); // Hitung total baris data (setelah header)

            foreach ($records as $key => $row) {
                $processedRowsCount++;
                $partNumber = trim($row['part_number'] ?? '');
                $partName = trim($row['part_name'] ?? '');
                $partPrice = trim($row['part_price'] ?? '');

                // Validasi dasar (bisa ditambahkan validasi lebih detail)
                if (empty($partNumber) || empty($partName) || !is_numeric($partPrice)) {
                     Log::warning("Baris ke-" . ($key + 1) . " dilewati karena data tidak lengkap atau format harga salah: " . json_encode($row));
                    continue; // Lewati baris jika data tidak valid
                }

                $masterPart = MasterPart::updateOrCreate(
                    ['part_number' => $partNumber],
                    [
                        'part_name' => $partName,
                        'part_price' => (float)$partPrice, // Pastikan harga adalah float/decimal
                    ]
                );

                if ($masterPart->wasRecentlyCreated) {
                    $newRecordsCount++;
                } elseif ($masterPart->wasChanged()) {
                    $updatedRecordsCount++;
                }
            }

            DB::commit(); // Commit transaksi jika semua berhasil

            $importLog->update([
                'status' => 'success',
                'total_rows' => $totalRowsInCsv,
                'processed_rows' => $processedRowsCount,
                'new_records' => $newRecordsCount,
                'updated_records' => $updatedRecordsCount,
            ]);

            Notification::make()
                ->title('Impor CSV Berhasil')
                ->body("Total baris di CSV: {$totalRowsInCsv}. Diproses: {$processedRowsCount}. Part baru: {$newRecordsCount}. Part diperbarui: {$updatedRecordsCount}.")
                ->success()
                ->send();

        } catch (\League\Csv\Exception $e) {
            DB::rollBack();
            $importLog->update(['status' => 'failed', 'error_message' => 'Kesalahan saat membaca file CSV: ' . $e->getMessage()]);
            Log::error('Kesalahan impor CSV (League\Csv): ' . $e->getMessage());
            Notification::make()->title('Impor Gagal')->body('Terjadi kesalahan saat membaca file CSV. Periksa format file. Detail: ' . $e->getMessage())->danger()->send();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            $importLog->update(['status' => 'failed', 'error_message' => 'Kesalahan database: ' . $e->getMessage()]);
            Log::error('Kesalahan impor CSV (Database): ' . $e->getMessage());
            Notification::make()->title('Impor Gagal')->body('Terjadi kesalahan database saat impor. Detail: ' . $e->getMessage())->danger()->send();
        } catch (\Exception $e) {
            DB::rollBack();
            $importLog->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
            Log::error('Kesalahan impor CSV (Umum): ' . $e->getMessage());
            Notification::make()->title('Impor Gagal')->body($e->getMessage())->danger()->send();
        } finally {
            // Hapus file yang diunggah setelah diproses (baik berhasil maupun gagal)
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
            // Refresh tabel untuk menampilkan data terbaru
            $this->dispatch('refreshTable'); // Filament v3+
        }
    }
    
}
