<?php

use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;


function sendMessage(string $phoneNumber, string $message, ?string $imagePath = null)
{
    // Path to the Node.js script
    $scriptPath = base_path('node_server\server.cjs');

    $message = base64_encode($message);
    $command = escapeshellcmd("node {$scriptPath} {$phoneNumber} " . escapeshellarg($message));

    if ($imagePath) {
        $command .= ' ' . escapeshellarg($imagePath);
    }

    // dd($command);

    // Execute the command and capture output
    $output = [];
    $returnVar = null;
    exec($command, $output, $returnVar);

    if ($returnVar === 0) {
        // Check if the output contains a success message
        $outputText = implode("\n", $output);
        if (str_contains($outputText, 'Message sent successfully')) {
            return [
                'success' => true,
                'message' => 'Message sent successfully!',
                'output' => $outputText,
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Message not sent. Check output logs.',
                'output' => $outputText,
            ];
        }
    } else {
        return [
            'success' => false,
            'message' => 'Node.js script execution failed.',
            'output' => implode("\n", $output),
        ];
    }
}

if (! function_exists('UploadImage')) {
    function UploadImage($request, $NameFile)
    {
        $min = 1;
        $max = 9000;
        // Create an array of all possible numbers
        $numbers = range($min, $max);
        // Shuffle the array to randomize the order
        shuffle($numbers);
        // Pick a unique random number
        $randomNumber = array_pop($numbers);
        $file = $request->file($NameFile);
        $extensions = $file->getClientOriginalExtension();
        $rename = 'data' . $randomNumber . '.' . $extensions;
        $file->storeAs('images', $rename, 'public');

        return $rename;
    }
}

    if (! function_exists('toRupiah')) {
     function toRupiah($angka)
     {
        return "Rp.". number_format($angka, 0, '.','.');
     }
    }

    if (! function_exists('normalizeString')) {
        function normalizeString($string) {
            return strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $string));
        }
    }


