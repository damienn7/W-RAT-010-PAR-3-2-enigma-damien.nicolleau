<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enigma</title>
    <style>
        * {
            box-sizing: border-box;
            padding: 0;
            margin: 0;
            font-family: 'Courier New', Courier, monospace;
        }

        button,
        input,
        select,
        textarea {
            font-family: inherit;
            font-size: 100%;
        }

        input,
        select {
            height: 2rem;
            width: 25vw;
        }

        body {
            padding: 5rem;
            background-color: black;
            color: white;
        }

        .group {
            margin: 0.5rem;
            display: flex;
            flex-wrap: wrap;
        }

        .group label {
            width: 100%;
        }

        img {
            position: absolute;
            right: 0;
            top: 0;
            width: 50vw;
            height: 100vh;
            filter: grayscale(80%);
            border-left: 1px solid white;
        }

        form {
            height: 30vh;
            width: 30vw;
        }

        form>.group {
            height: calc(100%/3);
        }

        .btn-submit {
            margin: 0.5rem;
            cursor: pointer;
        }

        .computed {
            height: 35vh;
            color: white;
            margin: 6rem 0.5rem 3rem 0.5rem;
            width: 30vw;
        }
    </style>
</head>

<body>
    <?php

    $dictionnary = [
        "a" => 0,
        "b" => 1,
        "c" => 2,
        "d" => 3,
        "e" => 4,
        "f" => 5,
        "g" => 6,
        "h" => 7,
        "i" => 8,
        "j" => 9,
        "k" => 10,
        "l" => 11,
        "m" => 12,
        "n" => 13,
        "o" => 14,
        "p" => 15,
        "q" => 16,
        "r" => 17,
        "s" => 18,
        "t" => 19,
        "u" => 20,
        "v" => 21,
        "w" => 22,
        "x" => 23,
        "y" => 24,
        "z" => 25
    ];

    function cesar_calc($letter, $hash_key, $dictionnary, $action ="hash") {
        if ($action == "hash") {
            $initial_pos = $dictionnary[$letter];
            if (($initial_pos + $hash_key) <= 25) {
                foreach ($dictionnary as $letter_name => $letter_position) {
                    if (($initial_pos + $hash_key) == $letter_position) {
                        return $letter_name;
                    }
                }
            } else {
                $flip_arr = array_flip($dictionnary);
                $divide = ($initial_pos + $hash_key) / 25;
                // echo "initial_pos : ".$initial_pos."<br>";
                // echo "hash_key : ".$hash_key."<br>";
                // echo "divide : ".$divide."<br>";
                // echo "addition : ".($initial_pos + $hash_key)."<br>";
                $merge_arr = $flip_arr;
                // var_dump($flip_arr);
                for ($i=0; $i < round($divide); $i++) {
                    $merge_arr = array_merge($merge_arr, $flip_arr);
                }
                // echo "<pre>";
                // var_dump($merge_arr);
                // echo "</pre>";

                $j = 0;
                foreach ($merge_arr as $letter_position => $letter_name) {
                    echo "letter name : ".$letter_name."<br>";
                    if ($j == ($initial_pos + $hash_key)) {
                        return $letter_name;
                    }
                    $j++;
                }
            }
        } else {
            $initial_pos = $dictionnary[$letter];
            if (($initial_pos - $hash_key) >= 0) {
                foreach ($dictionnary as $letter_name => $letter_position) {
                    if (($initial_pos - $hash_key) == $letter_position) {
                        return $letter_name;
                    }
                }
            } else {
                $letter_unhash_position = ($hash_key >= $initial_pos) ? 26 - ($hash_key - $initial_pos) : $initial_pos - $hash_key ;
                // echo $letter_unhash_position."<br>";
                return array_flip($dictionnary)[$letter_unhash_position];
            }
        }

        return "";
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        // var_dump($_POST);
        // : string
        $word = htmlspecialchars($_POST['word']);
        // hash or unhash
        $hash = htmlspecialchars($_POST['hash']);
        // cesar or vigenere or masque
        $type = htmlspecialchars($_POST['type']);
        // hash key for cesar algorythm
        $hash_key = (!empty(htmlspecialchars($_POST['cesar-key']))) ? htmlspecialchars($_POST['cesar-key']) : 2;
        switch ($type) {
            case 'cesar':
                $chain = "";
                for ($i = 0; $i < strlen($word); $i++) {
                    $letter = $word[$i];
                    if (strtolower($dictionnary[$letter]) != null) {
                        $chain .= cesar_calc(strtolower($letter), $hash_key, $dictionnary, $hash);
                    } else {
                        $chain .= $letter;
                    }
                }
                if ($hash) {
                } else {
                }
                break;
            case 'vigenere':
                // to do
                break;
            case 'masque':
                // to do
                break;
            default:
                // to do
                break;
        }
    } else {
        $computed = "Aucun résultat";
    }
    ?>
    <form action="enigma.php" method="POST">
        <div class="group">
            <label for="word">Saisir un mot ou une phrase :</label>
            <input type="text" id="word" name="word" required>
        </div>
        <div class="group">
            <label for="type">Type de chiffrement :</label>
            <select name="type" id="type" required>
                <option value="vigenere">Chiffrement de vigenère.</option>
                <option value="masque">Chiffrement du masque jetable.</option>
                <option value="cesar">Chiffrement de César.</option>
            </select>
            <div class="elToAppend" style="display: none;">
                <label for="cesar-key">Sélectionner la clé de chiffrement</label>
                <input id="cesar-key" name="cesar-key" type="number" min="2" max="26">
            </div>
        </div>
        <div class="group">
            <label for="hash">Chiffrer ou déchiffrer ?</label>
            <select name="hash" id="hash" required>
                <option value="hash">Chiffrer.</option>
                <option value="unhash">Déchiffrer.</option>
            </select>
        </div>
        <input type="submit" class="btn-submit" value="Lancer l'action">
    </form>
    <div class="computed">
        <?php 
        
        echo (isset($chain) && strlen($chain) > 0) ? $chain : "Aucun résultat.";

        ?>
    </div>
    <img src="./assets/enigmaLO.jpg" alt="Image d'une machine à écrire des années 20.">
    <script>
        window.onload = function(event) {
            let algoChoosed;
            document.getElementById("type").addEventListener('change', (e) => {
                console.log(e.target.value);
                if (e.target.value == "cesar") {
                    document.querySelector(".elToAppend").style.display = "";
                } else {
                    document.querySelector(".elToAppend").style.display = "none";
                }
            })
        }
    </script>
</body>

</html>