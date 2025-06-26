<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cubilete</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to bottom right, #4B0082, #8A2BE2);
            color: #fff;
            text-align: left; 
            padding-left: 20px; 
        }

        h1 {
            background-color: rgba(255, 255, 255, 0.9);
            color: #4B0082;
            padding: 20px;
            margin: 0;
            font-size: 3em;
            border-bottom: 4px solid #4B0082;
            width: 100%;
        }

        form {
            margin: 20px 0;
        }

        input[type="submit"] {
            font-size: 20px;
            padding: 10px 30px;
            margin: 10px;
            background-color: #fff;
            color: #4B0082;
            border: 2px solid #fff;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #4B0082;
            color: #fff;
            border-color: #fff;
        }

        p {
            background-color: rgba(255, 255, 255, 0.9);
            color: #000;
            font-size: 1.2em;
            margin: 20px 0;
            padding: 15px;
            display: inline-block;
            border-radius: 10px;
        }

        h2 {
            background-color: rgba(255, 255, 255, 0.9);
            color: #000;
            padding: 15px;
            border-radius: 10px;
            display: inline-block;
            margin: 10px 0;
        }

        img {
            margin: 5px;
            border-radius: 10px;
            box-shadow: 2px 2px 10px #000;
        }
    </style>
</head>
<body>

<h1>Cubilete</h1>

<?php
session_start();

$figuras = ['9', '10', 'J', 'Q', 'K', 'A'];

function tirarDados() {
    global $figuras;
    $dados = [];
    for ($i = 0; $i < 5; $i++) {
        $figuraAleatoria = $figuras[rand(0, 5)];
        $dados[] = $figuraAleatoria;
    }
    return $dados;
}

function detectarCombinacion($dados) {
    $conteo = array_count_values($dados);
    $valores = array_values($conteo);
    rsort($valores);

    if ($valores[0] == 5) return ['nombre' => 'Quintilla', 'valor' => 7];
    if ($valores[0] == 4) return ['nombre' => 'Poker', 'valor' => 6];
    if ($valores[0] == 3 && in_array(2, $valores)) return ['nombre' => 'Full', 'valor' => 5];
    if ($valores[0] == 3) return ['nombre' => 'Tercia', 'valor' => 4];
    if ($valores[0] == 2 && count(array_keys($conteo, 2)) == 2) return ['nombre' => 'Dos pares', 'valor' => 3];
    if ($valores[0] == 2) return ['nombre' => 'Un par', 'valor' => 2];
    return ['nombre' => 'Nada', 'valor' => 1];
}

if (isset($_POST['jugador1'])) $_SESSION['jugador1'] = tirarDados();
if (isset($_POST['jugador2'])) $_SESSION['jugador2'] = tirarDados();
if (isset($_POST['reiniciar'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

function mostrarDados($jugador) {
    global $figuras;
    $nombres = ['jugador1' => 'Ivonne', 'jugador2' => 'Jocelyn'];
    echo '<p>';
    echo $nombres[$jugador] . ': ';
    foreach ($_SESSION[$jugador] as $dado) {
        $indice = array_search($dado, $figuras) + 1;
        echo "<img src='imagenes/imagenes{$indice}.jpg' width='100' height='100'>";
    }
    $combinacion = detectarCombinacion($_SESSION[$jugador]);
    echo "<br>Combinación: <strong>" . $combinacion['nombre'] . "</strong>";
    echo '</p>';
}
?>

<form method="post">
    <input type="submit" name="jugador1" value="Ivonne" 
           <?php if (isset($_SESSION['jugador1'])) echo "disabled"; ?> />
    <input type="submit" name="jugador2" value="Jocelyn" 
           <?php if (!isset($_SESSION['jugador1']) || isset($_SESSION['jugador2'])) echo "disabled"; ?> />
</form>

<?php
if (isset($_SESSION['jugador1'])) mostrarDados('jugador1');
if (isset($_SESSION['jugador2'])) mostrarDados('jugador2');

if (isset($_SESSION['jugador1']) && isset($_SESSION['jugador2'])) {
    $mano1 = detectarCombinacion($_SESSION['jugador1']);
    $mano2 = detectarCombinacion($_SESSION['jugador2']);

    echo '<h2>';
    if ($mano1['valor'] > $mano2['valor']) {
        echo "¡Ivonne ganó con " . $mano1['nombre'] . "!";
    } elseif ($mano2['valor'] > $mano1['valor']) {
        echo "¡Jocelyn ganó con " . $mano2['nombre'] . "!";
    } else {
        echo "¡Empate con " . $mano1['nombre'] . "!";
    }
    echo '</h2>';

    echo '<form method="post">
        <input type="submit" name="reiniciar" value="Jugar de nuevo" />
    </form>';
}
?>

</body>
</html>
