<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = trim($_POST['password'] ?? '');
    $cost = intval($_POST['cost'] ?? 10);

    if ($password === '') {
        $error = 'Ingresa una contraseña.';
    } elseif ($cost < 4 || $cost > 15) {
        $error = 'El costo debe estar entre 4 y 15.';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => $cost]);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Generador de hash (password_hash)</title>
  <style>
    body { font-family: system-ui, sans-serif; max-width: 600px; margin: 40px auto; }
    label { display:block; margin:12px 0 6px; }
    input[type="text"], input[type="number"] { width:100%; padding:10px; }
    button { padding:10px 16px; margin-top:12px; }
    .out { margin-top:18px; padding:12px; background:#f6f8fa; border:1px solid #e1e4e8; }
    .error { color:#b00020; margin-top:8px; }
    code { word-break: break-all; }
  </style>
</head>
<body>
  <h1>Generar hash con password_hash</h1>
  <form method="post">
    <label for="password"><strong>Contraseña a encriptar:</strong></label>
    <input type="text" id="password" name="password" placeholder="Ej: 123456" required>

    <label for="cost"><strong>Cost (rounds, opcional):</strong></label>
    <input type="number" id="cost" name="cost" value="10" min="4" max="15">

    <button type="submit">Generar hash</button>
    <?php if (!empty($error)): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
  </form>

  <?php if (!empty($hash)): ?>
    <div class="out">
      <p><strong>Hash generado:</strong></p>
      <code><?= htmlspecialchars($hash) ?></code>
      <p><strong>Nota:</strong> Cada generación produce un hash distinto por la sal aleatoria. Úsalo con password_verify.</p>
      <p><strong>Ejemplo de INSERT:</strong></p>
      <code>INSERT INTO usuarios (nombre, email, password, rol)
VALUES ('Admin', 'admin@correo.com', '<?= htmlspecialchars($hash) ?>', 'admin');</code>
    </div>
  <?php endif; ?>
</body>
</html>