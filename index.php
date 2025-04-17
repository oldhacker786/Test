<?php
$result = "";

function fetch_sim_data($phone) {
    $api_url = "https://legendxdata.site/Api/simdata.php?phone=" . urlencode($phone);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = htmlspecialchars($_POST['phone']);
    $data = fetch_sim_data($phone);

    if (!empty($data) && is_array($data) && isset($data[0])) {
        $user = $data[0];

        $result = '<div class="result-box">
            <h3>🔎 SIM Data Found</h3>
            <p><strong>📞 Mobile:</strong> <span>' . ($user['Number'] ?? "Not found") . '</span></p>
            <p><strong>👤 Name:</strong> <span>' . ($user['Name'] ?? "Not found") . '</span></p>
            <p><strong>🆔 CNIC:</strong> <span>' . ($user['CNIC'] ?? "Not found") . '</span></p>
            <p><strong>📍 Address:</strong> <span>' . ($user['Address'] ?? "Not found") . '</span></p>
            <p><strong>📶 Operator:</strong> <span>' . ($user['Operator'] ?? "Not found") . '</span></p>
            <button class="copy-btn" onclick="copyData()">📋 Copy All Data</button>
            <button class="whatsapp-btn" onclick="shareOnWhatsApp()">📤 Share on WhatsApp</button>
        </div>';
    } else {
        $result = '<div class="result-box error">❌ No data found for this number.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIM Data Lookup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 50px;
            background: #121212;
            color: #fff;
        }
        .container {
            background: #1e1e1e;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px red;
            max-width: 400px;
            margin: auto;
        }
        h2 {
            color: red;
        }
        input, .btn {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 5px;
            border: none;
            background: #2a2a2a;
            color: white;
            font-size: 16px;
        }
        .btn {
            background: linear-gradient(to right, red, darkred);
            cursor: pointer;
        }
        .btn:hover {
            background: linear-gradient(to right, darkred, red);
        }
        .result-box {
            background: #111;
            padding: 15px;
            margin-top: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px red;
            text-align: left;
        }
        .result-box h3 {
            color: yellow;
            text-align: center;
            text-transform: uppercase;
            border-bottom: 2px solid yellow;
            padding-bottom: 5px;
        }
        .result-box p {
            font-size: 18px;
            margin: 8px 0;
        }
        .result-box strong {
            color: cyan;
        }
        .copy-btn, .whatsapp-btn {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            color: white;
        }
        .copy-btn {
            background: red;
        }
        .copy-btn:hover {
            background: darkred;
        }
        .whatsapp-btn {
            background: green;
        }
        .whatsapp-btn:hover {
            background: darkgreen;
        }
        .error {
            background: #ff4444;
            box-shadow: 0 0 10px red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>🔍 Search SIM Details</h2>
        <form method="POST">
            <input type="text" name="phone" placeholder="Enter phone number" required>
            <button type="submit" class="btn">🔎 Get Details</button>
        </form>
        <div id="response">
            <?php echo $result; ?>
        </div>
        <a href="https://whatsapp.com/channel/0029VajZpqL7T8bRvUqAU23A" target="_blank">
            <button class="btn">💬 Contact Developer</button>
        </a>
    </div>

    <script>
        function copyData() {
            let text = document.querySelector(".result-box").innerText;
            navigator.clipboard.writeText(text).then(() => {
                alert("✅ Data copied to clipboard!");
