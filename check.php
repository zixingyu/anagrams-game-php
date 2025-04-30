<?php
$targetLetters = str_split('SHRLINA');
sort($targetLetters);
$targetKey = implode('', $targetLetters);

$words = file('words7.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$found = false;
foreach ($words as $word) {
    $letters = str_split(strtoupper(trim($word)));
    sort($letters);
    if (implode('', $letters) === $targetKey) {
        echo "✅ FOUND MATCH: $word<br>";
        $found = true;
    }
}

if (!$found) {
    echo "❌ No match found";
}
?>

