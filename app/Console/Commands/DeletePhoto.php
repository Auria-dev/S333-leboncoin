<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Photo;

class DeletePhoto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'photo:delete-photos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Supprimer les photos de public/images, non utilisées dans la base de données';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $photos = Photo::pluck('nomphoto')->map(function ($path) {
            return str_replace('/images/', '', $path);
        })->toArray();

        $dossier = public_path('images');
        $files = File::files($dossier);
        $count = 0;
        $ignore = 'photo-annonce.jpg';

        foreach($files as $file) {
            $fileName = $file->getFileName();
            if(!in_array($fileName, $photos) && $fileName !== $ignore) {
                File::delete($file->getRealPath());
                $this->info("Supprimé : $fileName");
                $count++;
            }
        }
        $this->info("Suppression de $count images.");
    }
}
