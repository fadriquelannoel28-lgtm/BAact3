<?php
session_start();

$firstNameErr = $lastNameErr = $emailErr = $passwordErr = $confirmPasswordErr = $termsErr = "";
$firstName = $lastName = $email = $password = $confirmPassword = "";
$successMsg = $existErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = trim($_POST['firstName'] ?? '');
    $lastName = trim($_POST['lastName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    $terms = isset($_POST['terms']);

    $namePattern = "/^[a-zA-Z\s]{2,}$/";
    $emailPattern = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";

    if (empty($firstName)) {
        $firstNameErr = "First name is required.";
    } elseif (!preg_match($namePattern, $firstName)) {
        $firstNameErr = "First name must have at least 2 characters (letters only).";
    }

    if (empty($lastName)) {
        $lastNameErr = "Last name is required.";
    } elseif (!preg_match($namePattern, $lastName)) {
        $lastNameErr = "Last name must have at least 2 characters (letters only).";
    }

    if (empty($email)) {
        $emailErr = "Email address is required.";
    } elseif (!preg_match($emailPattern, $email)) {
        $emailErr = "Please enter a valid email address (e.g. name@example.com).";
    }

    if (empty($password)) {
        $passwordErr = "Please provide a password.";
    } elseif (strlen($password) < 6) {
        $passwordErr = "Password must be at least 6 characters long.";
    }

    if (empty($confirmPassword)) {
        $confirmPasswordErr = "Please confirm your password.";
    } elseif ($password !== $confirmPassword) {
        $confirmPasswordErr = "Passwords do not match.";
    }

    if (!$terms) {
        $termsErr = "You must agree to the disposal guidelines.";
    }

    if (empty($firstNameErr) && empty($lastNameErr) && empty($emailErr) && empty($passwordErr) && empty($confirmPasswordErr) && empty($termsErr)) {
        $defaultAdminEmail = "admin@ecocoins.ph";
        $storedUsers = $_SESSION['ecoCoinsUsers'] ?? [];

        $emailExists = (strtolower($email) === strtolower($defaultAdminEmail));
        if (!$emailExists) {
            foreach ($storedUsers as $user) {
                if (strtolower($user['email']) === strtolower($email)) {
                    $emailExists = true;
                    break;
                }
            }
        }

        if ($emailExists) {
            $existErr = "An account with this email already exists.";
        } else {
            $storedUsers[] = [
                'firstName' => $firstName,
                'lastName' => $lastName,
                'email' => $email,
                'password' => $password
            ];
            $_SESSION['ecoCoinsUsers'] = $storedUsers;
            $successMsg = "Account created successfully! Welcome, " . htmlspecialchars($firstName) . "!";

            $firstName = $lastName = $email = "";
        }
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - EcoCoins</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
      .error-text {
        color: #dc3545;
        font-size: 0.85rem;
        margin-top: 0.25rem;
        display: block;
        font-weight: 500;
      }
    </style>
  </head>
  <body class="bg-light min-vh-100 d-flex flex-column justify-content-between">

    <nav class="navbar navbar-expand-lg navbar-dark bg-success border-bottom border-light">
      <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">EcoCoins</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="index.php#hero">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php#about">About</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php#how-it-works">How It Works</a></li>
            <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
            <li class="nav-item"><a class="nav-link active fw-bold" href="register.php">Register</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <main class="container py-5 my-auto">
      <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
          <div class="card border-success shadow-sm bg-white">
            <div class="card-header bg-success text-white text-center py-3">
              <h4 class="fw-bold mb-0">Create an EcoCoins Account</h4>
            </div>
            <div class="card-body p-4">

              <?php if (!empty($existErr)): ?>
                <div class="alert alert-warning py-2 small" role="alert">
                  <strong>Notice:</strong> <?php echo htmlspecialchars($existErr); ?> <a href="login.php" class="alert-link text-success fw-bold">Log in here</a>.
                </div>
              <?php endif; ?>

              <?php if (!empty($successMsg)): ?>
                <div class="alert alert-success py-2 small" role="alert">
                  <strong>Account Created!</strong> <?php echo $successMsg; ?> <a href="login.php" class="alert-link text-success fw-bold">Log In Here</a>.
                </div>
              <?php endif; ?>

              <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" novalidate>
                <div class="row g-3">
                  <div class="col-md-6">
                    <label for="firstName" class="form-label text-dark fw-semibold">First Name</label>
                    <input type="text" class="form-control border-success <?php echo !empty($firstNameErr) ? 'is-invalid' : ''; ?>" id="firstName" name="firstName" value="<?php echo htmlspecialchars($firstName); ?>" placeholder="Juan">
                    <?php if (!empty($firstNameErr)): ?>
                      <span class="error-text"><?php echo $firstNameErr; ?></span>
                    <?php endif; ?>
                  </div>
                  <div class="col-md-6">
                    <label for="lastName" class="form-label text-dark fw-semibold">Last Name</label>
                    <input type="text" class="form-control border-success <?php echo !empty($lastNameErr) ? 'is-invalid' : ''; ?>" id="lastName" name="lastName" value="<?php echo htmlspecialchars($lastName); ?>" placeholder="Dela Cruz">
                    <?php if (!empty($lastNameErr)): ?>
                      <span class="error-text"><?php echo $lastNameErr; ?></span>
                    <?php endif; ?>
                  </div>
                  <div class="col-12">
                    <label for="email" class="form-label text-dark fw-semibold">Email Address</label>
                    <input type="email" class="form-control border-success <?php echo !empty($emailErr) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="juan@example.com">
                    <?php if (!empty($emailErr)): ?>
                      <span class="error-text"><?php echo $emailErr; ?></span>
                    <?php endif; ?>
                  </div>
                  <div class="col-md-6">
                    <label for="password" class="form-label text-dark fw-semibold">Password</label>
                    <input type="password" class="form-control border-success <?php echo !empty($passwordErr) ? 'is-invalid' : ''; ?>" id="password" name="password" placeholder="Create password">
                    <?php if (!empty($passwordErr)): ?>
                      <span class="error-text"><?php echo $passwordErr; ?></span>
                    <?php endif; ?>
                  </div>
                  <div class="col-md-6">
                    <label for="confirmPassword" class="form-label text-dark fw-semibold">Confirm Password</label>
                    <input type="password" class="form-control border-success <?php echo !empty($confirmPasswordErr) ? 'is-invalid' : ''; ?>" id="confirmPassword" name="confirmPassword" placeholder="Confirm password">
                    <?php if (!empty($confirmPasswordErr)): ?>
                      <span class="error-text"><?php echo $confirmPasswordErr; ?></span>
                    <?php endif; ?>
                  </div>
                  <div class="col-12">
                    <div class="form-check">
                      <input class="form-check-input border-success <?php echo !empty($termsErr) ? 'is-invalid' : ''; ?>" type="checkbox" id="terms" name="terms" <?php echo isset($_POST['terms']) ? 'checked' : ''; ?>>
                      <label class="form-check-label text-secondary small" for="terms">
                        I agree to participate in proper waste disposal guidelines.
                      </label>
                      <?php if (!empty($termsErr)): ?>
                        <span class="error-text"><?php echo $termsErr; ?></span>
                      <?php endif; ?>
                    </div>
                  </div>
                  <div class="col-12">
                    <button type="submit" class="btn btn-success w-100 fw-bold py-2 mt-2">Create Account</button>
                  </div>
                </div>
              </form>

            </div>
            <div class="card-footer bg-light border-top border-success text-center py-3">
              <span class="text-secondary small">Already registered? </span>
              <a href="login.php" class="text-success fw-bold text-decoration-none">Log In Here</a>
            </div>
          </div>
          <div class="text-center mt-3">
            <a href="index.php" class="text-secondary small text-decoration-none">&larr; Back to Main Proposal</a>
          </div>
        </div>
      </div>
    </main>

    <footer class="bg-white border-top border-success text-center py-3">
      <div class="container">
        <p class="text-muted small mb-0">&copy; EcoCoins Project Proposal. Lipa City Malls Recycling Reward System.</p>
      </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
