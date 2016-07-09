<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <form action="page.php" method="POST" enctype="multipart/form-data">
            <table cellspacing="0" style="margin: auto">

                <?php
                if (isset($_POST['enviar1'])) {
                    $nQuestoes = $_POST['nQuestoes'];

                    echo '<br>';
                    for ($i = 1; $i <= $nQuestoes; $i++) {

                        echo '<tr>';
                        echo '<td>Questão ' . $i . ': </td>';
                        echo '<td><textarea name="questao[]" rows="4" cols="40"></textarea></td>';
                        echo '<td><input type="file" name="img[]" value="null"></td>';
                        echo '</tr>';
                    }
                    echo '<input type="hidden" name="nQuestoes" value="' . $nQuestoes . '">';
                    ?>
                    <tr>
                        <td></td>
                        <td align="right">
                            <input name='enviar2' type="submit" value="Enviar">
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </form>
        <?php
        if (isset($_POST['enviar2'])) {

            $questoes = $_POST['questao'];
            $nQuestoes = $_POST['nQuestoes'];
            $imagens = $_FILES['img'];


            for ($i = 0; $i < $nQuestoes; $i++) {
                if (empty($imagens['tmp_name'][$i])) {
                    $imagens['tmp_name'][$i] = 'sem imagem';
                }
            }

            function array_combine_($keys, $values) {
                $result = array();
                foreach ($keys as $i => $k) {
                    $result[$k] = $values[$i];
                }
                return $result;
            }

            $resultado = array_combine_($questoes, $imagens['tmp_name']);

            function shuffle_assoc($list) {
                if (!is_array($list))
                    return $list;

                $keys = array_keys($list);
                shuffle($keys);
                $random = array();
                foreach ($keys as $key)
                    $random[$key] = $list[$key];

                return $random;
            }

            for ($i = 1; $i <= 3; $i++) {

                $embaralhado = shuffle_assoc($resultado);

                $conteudoPagina = '<html>
              <head>
              <meta charset="UTF-8">
              <title></title>
              </head>
              <body>
              <table>';
                $b = 1;
                foreach ($embaralhado as $key => $value) {

                    if ($value == 'sem imagem') {
                        $conteudoPagina .= '<tr><td>' . $b. ') ' . nl2br($key) . '</td></tr>';
                    } else {

                        $conteudoPagina .= '<tr><td>' . $b . ') </td></tr>';
                        $conteudoPagina .= '<tr><td><img src="' . $value . '" style="margin:auto;"></td></tr>';
                        $conteudoPagina .= '<tr><td>' . nl2br($key) . '</td></tr><br><br>';
                    }
                    $b++;
                }
                $conteudoPagina .= '</table>
              </body>
              </html>';

                ob_start();
                $html = ob_get_clean();
                $html = utf8_encode($html);

                require_once './mpdf60/mpdf.php';
                $Mpdf = new mPDF();
                $Mpdf->allow_charset_conversion = true;
                $Mpdf->charset_in = 'UTF-8';
                $Mpdf->WriteHTML($conteudoPagina);
                $Mpdf->Output('prova' . $i . '.pdf', 'F');
            }
            echo 'Suas provas foram geradas com sucesso. Verifique o diretório.';
        }
        ?>
    </body>
</html>
