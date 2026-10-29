<?php

function lerArquivo($arquivo_nome) {
    $arquivo = fopen($arquivo_nome, "r");
    if (!$arquivo) {
        return [];
    }

    $resultado = []; 

    while (($linha = fgets($arquivo)) !== false) {
        
        $linha = trim($linha);

        if ($linha === '') {
            continue;
        }

        $resultado[] = explode('###', $linha);
    }

    fclose($arquivo); 

    return $resultado;
}

function salvarArquivo($arquivo_nome, $conteudo) {
    $arquivo = fopen($arquivo_nome, "w");

    if ($arquivo) {
        if (is_array($conteudo)) {
            foreach ($conteudo as $linha_dados) {
                if (is_array($linha_dados)) {
                    $texto = implode('###', $linha_dados) . PHP_EOL;
                } else {
                    $texto = $linha_dados . PHP_EOL;
                }
                fwrite($arquivo, $texto);
            }
        } else {
            fwrite($arquivo, $conteudo);
        }
        
        fclose($arquivo);
    }
}

function proximoId($lista_itens) {
    if (empty($lista_itens)) {
        return 1;
    }

    $maior_id = 0;

    foreach ($lista_itens as $item) {
        if (isset($item[0])) {
            $id_atual = (int)$item[0];
            
            if ($id_atual > $maior_id) {
                $maior_id = $id_atual;
            }
        }
    }

    return $maior_id + 1;
}