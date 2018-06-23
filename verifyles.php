<?php

    if (count($argv) < 2) {
        echo "Nenhum parâmetro passado.\n
        Exemplo de uso:\n\n
        php verify-photos.php <diretório-a-ser-varrido> <extensão-de-arquivos-procurada>\n\n";
        die;
    }

    $path = (string) $argv[1];
    $extension = null;
    if ($argv[2] != null) {
        $extension = $argv[2];
    }
    
    chdir($path);
    echo "Changed to directory: $path \n\n";
    if ($extension != null) {
        $files = shell_exec("ls | grep .$extension");
    } else {
        $files = shell_exec("ls");
    }
    $files = explode("\n", $files);
        
    
    foreach ($files as $file) {
        if ($file != "" && $file != "." && $file != "..") {
            $answer = initialChoice($file);
            treatAnswer($answer, $file);
        }
    }



function initialChoice($file = null)
{
    echo "Arquivo alvo: $file \n\n 
O que deseja fazer?\n\n
(1) Abrir e verificar do que se trata.
(2) Renomear arquivo.
(3) Mover para uma lixeira temporária.
(4) Não fazer nada e pular para o próximo arquivo.\n\n";
    $answer = null;
    $answer = strtoupper(readline("Opção: "));
    if ($answer != '1' && $answer != '2' && $answer != '3') {
        echo "Escolha uma opção válida. ";
        initialChoice($file);
    }

    return $answer;
}

function treatAnswer($answer, $file)
{
    switch ($answer) {
        case '1':
            echo "Você escolheu a opção 1\n";
            echo "Abrindo arquivo $file \n";
            shell_exec("xdg-open '$file'\n");
            treatAnswer(initialChoice($file), $file);
            break;
        case '2':


        case '3':
            echo "Você escolheu a opção 3 - Mover para uma lixeira temporária.\n";

            if (!file_exists('./trash')) {
                shell_exec("mkdir ./trash");
            }
            shell_exec("echo '$file' >> ./trash/files-to-remove.txt");
            return;
        case '4':
            echo "Pulando para o próximo arquivo...\n";
            return;
    }
}

function addToRemovingFiles($file)
{
    array_push($removingFiles, $file);
    return $removingFiles;
}

// foreach ($files as $file) {
//     if ($file != "" && $file != "." && $file != "..") {
//         $isOpenFile = null;
//         $isOpenFile = strtoupper(readline("Deseja abrir o arquivo: $file (Y/n):\n"));
//         if ($isOpenFile == "Y") {
//             echo "Abrindo arquivo $file \n";
//             shell_exec("xdg-open '$file'\n");
//             $isRemFile = strtoupper(readline("Deseja mover o arquivo '$file' para a lixeira temporária para posterior exclusão?(N/y):"));
//             if ($isRemFile == "Y") {
//                 if (!file_exists("./trash")) {
//                     shell_exec("mkdir ./trash");
//                     shell_exec("mv '$file' ./trash");
//                 } else {
//                     shell_exec("mv '$file' ./trash");
//                 }
//             }

//         }
//     }
// }