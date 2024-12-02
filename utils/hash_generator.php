<?php
$generatedHash = '';
$verificationResult = '';
$error = '';
$verificationError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Determine which form was submitted
    if (isset($_POST['generate_hash'])) {
        // Handle Hash Generation
        $inputString = trim($_POST['inputString']); // Sanitize input

        if (!empty($inputString)) {
            // Generate the hash using password_hash
            $generatedHash = password_hash($inputString, PASSWORD_DEFAULT);
        } else {
            $error = "Please enter valid text to generate a hash.";
        }
    } elseif (isset($_POST['verify_hash'])) {
        // Handle Hash Verification
        $inputStringVerify = trim($_POST['inputStringVerify']);
        $hashToVerify = trim($_POST['hashToVerify']);

        if (empty($inputStringVerify) || empty($hashToVerify)) {
            $verificationError = "Please provide both the string and the hash for verification.";
        } else {
            // Replace $2y$ with $2b$ if necessary for compatibility
            if (strpos($hashToVerify, '$2y$') === 0) {
                $hashToVerify = '$2b$' . substr($hashToVerify, 4);
            }

            // Verify the password against the hash
            if (password_verify($inputStringVerify, $hashToVerify)) {
                $verificationResult = "✅ The input string matches the hash.";
            } else {
                $verificationResult = "❌ The input string does NOT match the hash.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hash Generator & Verifier</title>
    <link rel="stylesheet" href="css/registration.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        /* Basic styling for demonstration purposes */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .form-box {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            flex: 1 1 45%;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        h2 {
            margin-top: 0;
        }
        .information-box {
            position: relative;
            margin-bottom: 15px;
        }
        .information-box input {
            width: 100%;
            padding: 10px;
            padding-right: 40px;
            box-sizing: border-box;
        }
        .information-box i {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #555;
        }
        button {
            padding: 10px 15px;
            background: #28a745;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 3px;
        }
        button:hover {
            background: #218838;
        }
        .result-box {
            margin-top: 15px;
            padding: 10px;
            background: #e9ecef;
            border-radius: 3px;
            word-break: break-all;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <h1>Hash Generator & Verifier</h1>
    <div class="container">
        <!-- Hash Generation Form -->
        <div class="form-box">
            <form method="POST" action="">
                <h2>Generate Hash</h2>
                <div class="information-box">
                    <input type="text" name="inputString" placeholder="Enter text to hash" required>
                    <i class='bx bxs-file'></i>
                </div>
                <button type="submit" name="generate_hash">Generate Hash</button>
                <?php if ($generatedHash): ?>
                <div class="result-box">
                    <p><strong>Generated Hash:</strong></p>
                    <p><?php echo htmlspecialchars($generatedHash); ?></p>
                </div>
                <?php elseif ($error): ?>
                <div class="result-box error">
                    <p><?php echo htmlspecialchars($error); ?></p>
                </div>
                <?php endif; ?>
            </form>
        </div>

        <!-- Hash Verification Form -->
        <div class="form-box">
            <form method="POST" action="">
                <h2>Verify Hash</h2>
                <div class="information-box">
                    <input type="text" name="inputStringVerify" placeholder="Enter text to verify" required>
                    <i class='bx bxs-check-shield'></i>
                </div>
                <div class="information-box">
                    <input type="text" name="hashToVerify" placeholder="Enter hash to verify against" required>
                    <i class='bx bxs-hash'></i>
                </div>
                <button type="submit" name="verify_hash">Verify Hash</button>
                <?php if ($verificationResult): ?>
                <div class="result-box">
                    <p><?php echo htmlspecialchars($verificationResult); ?></p>
                </div>
                <?php elseif ($verificationError): ?>
                <div class="result-box error">
                    <p><?php echo htmlspecialchars($verificationError); ?></p>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>
</html>
