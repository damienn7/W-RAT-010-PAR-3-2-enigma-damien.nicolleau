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
            height: calc(100%/2.5);
        }

        .btn-submit {
            margin: 0.5rem;
            cursor: pointer;
        }

        .computed {
            height: 35vh;
            color: white;
            margin: 10rem 0.5rem 3rem 0.5rem;
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

    function cesar_calc($letter, $hash_key, $dictionnary, $action = "hash")
    {
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
                for ($i = 0; $i < round($divide); $i++) {
                    $merge_arr = array_merge($merge_arr, $flip_arr);
                }
                // echo "<pre>";
                // var_dump($merge_arr);
                // echo "</pre>";

                $j = 0;
                foreach ($merge_arr as $letter_position => $letter_name) {
                    echo "letter name : " . $letter_name . "<br>";
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
                $letter_unhash_position = ($hash_key >= $initial_pos) ? 26 - ($hash_key - $initial_pos) : $initial_pos - $hash_key;
                // echo $letter_unhash_position."<br>";
                return array_flip($dictionnary)[$letter_unhash_position];
            }
        }

        return "";
    }

    function generateVigenereTable()
    {
        $table = [];
        $alphabet = range('A', 'Z'); // Alphabet de A à Z

        for ($i = 0; $i < 26; $i++) {
            $row = [];
            for ($j = 0; $j < 26; $j++) {
                // On déplace l'index de l'alphabet circulairement
                $row[] = $alphabet[($i + $j) % 26];
            }
            $table[] = $row;
        }

        return $table;
    }

    // $chain .= vigenere_calc(strtolower($repeat_key[$i]), $letter, $vigenere_table, $hash, $dictionnary);
    function vigenere_calc($repeat_key_letter, $letter, $vigenere_table, $hash, $dictionnary)
    {
        if ($hash == "hash") {
            return $vigenere_table[$dictionnary[$repeat_key_letter]][$dictionnary[$letter]];
        } elseif ($hash == "unhash") {
            $index = array_flip($vigenere_table[$dictionnary[$repeat_key_letter]])[ucfirst($letter)];
            return array_flip($dictionnary)[$index];
        }
    }

        // $chain .= vigenere_calc(strtolower($repeat_key[$i]), $letter, $vigenere_table, $hash, $dictionnary);
        function mask_calc($repeat_key_letter, $letter, $vigenere_table, $hash, $dictionnary)
        {
            if ($hash == "hash") {
                $add = $dictionnary[$repeat_key_letter] + $dictionnary[$letter];
                echo $add;
                if ($add <= 25) {
                    return array_flip($dictionnary)[$add];
                } else {
                    $sous = $add - 25;
                    return array_flip($dictionnary)[$sous];
                }
                // return $vigenere_table[$dictionnary[$repeat_key_letter]][$dictionnary[$letter]];
            } elseif ($hash == "unhash") {
                $sous = $dictionnary[$repeat_key_letter] - $dictionnary[$letter];
                echo $sous;
                if ($sous >= 0) {
                    return array_flip($dictionnary)[$sous];
                } else {
                    $add = $sous + 25;
                    return array_flip($dictionnary)[$sous];
                }
                // $index = array_flip($vigenere_table[$dictionnary[$repeat_key_letter]])[ucfirst($letter)];
                // return array_flip($dictionnary)[$index];
            }
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
        // hash key for vigenere algorythm
        $hash_key_vigenere = (!empty(htmlspecialchars($_POST['vigenere-key']))) ? htmlspecialchars($_POST['vigenere-key']) : "MUSIQUE";
        // hash key for mask algorythm
        $hash_key_mask = (!empty(htmlspecialchars($_POST['mask-key']))) ? htmlspecialchars($_POST['mask-key']) : "";
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
                break;
            case 'vigenere':
                // to do
                $vigenere_table = generateVigenereTable();
                $repeat_word = $hash_key_vigenere;
                $repeat_word_const = $repeat_word;
                $repeat_key = "";
                $x = 0;
                for ($i = 0; $i < strlen($word); $i++) {
                    if ($dictionnary[strtolower($word[$i])] != null || is_int($dictionnary[strtolower($word[$i])])) {
                        if ($repeat_word[$x] != null) {
                            $repeat_key .= $repeat_word[$x];
                        } else {
                            $repeat_word .= $repeat_word_const;
                            $repeat_key .= $repeat_word[$x];
                        }
                        $x++;
                    } else {
                        $repeat_key .= $word[$i];
                    }
                }

                $chain = "";
                for ($i = 0; $i < strlen($word); $i++) {
                    $letter = strtolower($word[$i]);
                    if (strtolower($dictionnary[$letter]) != null) {
                        $chain .= vigenere_calc(strtolower($repeat_key[$i]), $letter, $vigenere_table, $hash, $dictionnary);
                    } else {
                        $chain .= $letter;
                    }
                }
                break;
            case 'masque':
                // to do
                $vigenere_table = generateVigenereTable();
                if (strlen($hash_key_mask) == 0 || $hash != "unhash") {
                    for ($i = 0; $i < strlen($word); $i++) {
                        $rd_int = random_int(0, 25);
                        $letter = strtolower($word[$i]);
                        if (strtolower($dictionnary[$letter]) != null) {
                            $repeat_word .= array_flip($dictionnary)[$rd_int];
                        } else {
                            $repeat_word .= $word[$i];
                        }
                    }
                } else {
                    $repeat_word = $hash_key_mask;
                }
                // echo "repeat word : " . $repeat_word."<br>";

                $chain = "";
                for ($i = 0; $i < strlen($word); $i++) {
                    $letter = strtolower($word[$i]);
                    if (strtolower($dictionnary[$letter]) != null) {
                        $chain .= vigenere_calc(strtolower($repeat_word[$i]), $letter, $vigenere_table, $hash, $dictionnary);
                    } else {
                        $chain .= $letter;
                    }
                }
                break;
            default:
                $chain = "Aucun résultat";
                break;
        }
    } else {
        $chain = "Aucun résultat";
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
                <label for="cesar-key">Sélectionner la clé de chiffrement (chiffre)</label>
                <input id="cesar-key" name="cesar-key" type="number" min="2" max="26">
            </div>
            <div class="elToAppendVigenere" style="">
                <label for="vigenere-key">Choisir la clé de chiffrement (texte)</label>
                <input id="vigenere-key" name="vigenere-key" type="text" min="2" max="26">
            </div>
            <div class="elToAppendMask" style="display: none;">
                <label for="mask-key">Choisir la clé de chiffrement (texte >= au mot ou à la phrase à chiffrer)<?php if (isset($repeat_word) && strlen($repeat_word) > 0): ?><span style="color: green;">Clé générée : <?= $repeat_word ?></span><?php endif; ?></label>
                <input id="mask-key" name="mask-key" type="text" min="2" max="26">
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

                if (e.target.value == "vigenere") {
                    document.querySelector(".elToAppendVigenere").style.display = "";
                } else {
                    document.querySelector(".elToAppendVigenere").style.display = "none";
                }

                if (e.target.value == "masque") {
                    document.querySelector(".elToAppendMask").style.display = "";
                } else {
                    document.querySelector(".elToAppendMask").style.display = "none";
                }

            })
        }
    </script>
</body>

</html>