<?php

$path = $argv[1];
if (!empty($argv[2])){
    $extension = $argv[2];
}

chdir($path);

$files = scandir($path);

foreach ($files as $file) {
    if ($file != "" && $file != "." && $file != "..") {
        $file = $path."/".$file;
        $answer = choice($file);
        treat_answer($answer, $file);
        
    }
}

function choice($file = null)
{
    echo "Arquivo alvo: $file \n\n
O que deseja fazer?\n\n
(1) Abrir e verificar do que se trata.
(2) Renomear arquivo.
(3) Mover para uma lixeira temporária.
(4) Pular para o próximo arquivo.\n
(q) Sair\n\n";
    $answer = null;
    $answer = strtoupper(readline("Opção: "));
    PHP_EOL;
    if ($answer != '1' && $answer != '2' && $answer != '3' && $answer != '4' && $answer != "Q") {
        echo "Escolha uma opção válida. ";
        choice($file);
    }

    return $answer;
}

function treat_answer($answer, $file)
{
    switch ($answer) {
        case '1':
            echo "Você escolheu a opção 1\n";
            echo "Abrindo arquivo $file \n";
            shell_exec("xdg-open '$file'\n");
            treat_answer(choice($file), $file);
            break;
        case '2':
            echo "Você escolheu a opção 2\n";
            $file_exploded = explode(".", $file);
            $file_extension = end($file_exploded);
            $new_name = readline("Insira um novo nome para o arquivo: ").".".$file_extension;
            rename($file, $new_name);
            treat_answer(choice($new_name), $new_name);
            break;
        case '3':
            echo "Você escolheu a opção 3 - Mover para uma lixeira temporária.\n";
            if (!file_exists('./trash')) {
                mkdir("./trash");
            }
            put_file_in_tmp_trash($file);
            return;
        case '4':
            echo "\nPulando para o próximo arquivo...\n===============================\n";
            return;
        case 'Q':
            get_files_in_tmp_trash();
            
            echo "Saindo...\n";
            die();
    }
}

function put_file_in_tmp_trash($file)
{
    $ftr = fopen("./trash/files-to-remove.txt", "a");
    fwrite($ftr, PHP_EOL);
    fwrite($ftr, $file);
    fclose($ftr);
}
function get_files_in_tmp_trash(){
    if (file_exists("./trash/files-to-remove.txt")){
        $arr_files_to_remove = files_in_tmp_trash_to_array();
        foreach ($arr_files_to_remove as $key => $file) {
            $line = "\n";
            if ($key == (count($arr_files_to_remove)-1)) {
                $line = "\n\n";
            }
            echo $file.$line;
        }
        $remove = strtoupper(readline("\n Deseja realmente remover esses arquivos?(y/N)"));
        if ($remove === "Y") {

        }
    }
    echo "Não foi inserido nenhum arquivo para remoção";
}

function files_in_tmp_trash_to_array()
{
    $ftr = fopen("./trash/files-to-remove.txt", "r");
    $files_to_remove = fread($ftr, filesize("./trash/files-to-remove.txt"));
    $arr_files_to_remove = explode("\n", $files_to_remove);

    return $arr_files_to_remove;
}