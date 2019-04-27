<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use TypeError;

class GenerateAppKeyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'key:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates new app key and saves to .env file';

    /**
     * Execute the console command.
     *
     * @return mixed
     *
     * @throws TypeError
     */
    public function handle()
    {
        $key = Str::random(32);

        if (file_exists($envFilePath = $this->getPathToEnvFile()) === false) {
            $this->info("Could not find env file! Key: $key");
        }

        if ($this->updateEnvFile($envFilePath, $key)) {
            $this->info("File .env updated with key: $key");
        }
    }

    /**
     * Retrieves env file path.
     *
     * @return string
     */
    private function getPathToEnvFile()
    {
        return base_path('.env');
    }


    /**
     * Adds a random 32 character string to env file.
     *
     * @param $path
     * @param $key
     *
     * @return bool|int
     */
    private function updateEnvFile($path, $key)
    {
        if (file_exists($path)) {

            $existingEnvValues = file_get_contents($path);
            $search = 'APP_KEY=' . env('APP_KEY');

            if (!Str::contains($existingEnvValues, $search)) {
                $search = 'APP_KEY=';
            }

            $newContent = str_replace($search, 'APP_KEY=' . $key, $existingEnvValues);

            return file_put_contents($path, $newContent);
        }

        return false;
    }
}