<?php
// Define constants for the game rules
const WARRIORS_FILE = 'warriors.json';
const DAMAGE_PER_HIT = 5;
const MAX_DAMAGE = 100;

$messages = [];
$warriors = [];

// --- Persistence Functions ---

/**
 * Loads the warriors data from the JSON file.
 * @return array The list of warriors.
 */
function loadWarriors(): array {
    if (!file_exists(WARRIORS_FILE) || filesize(WARRIORS_FILE) === 0) {
        return [];
    }
    $json = file_get_contents(WARRIORS_FILE);
    return json_decode($json, true) ?: [];
}

/**
 * Saves the current warriors data to the JSON file.
 * @param array $warriors The list of warriors to save.
 * @return bool True on success, false on failure.
 */
function saveWarriors(array $warriors): bool {
    // Sort warriors by name before saving for consistent display
    usort($warriors, fn($a, $b) => strcmp($a['name'], $b['name']));
    $json = json_encode($warriors, JSON_PRETTY_PRINT);
    return file_put_contents(WARRIORS_FILE, $json) !== false;
}

// --- Game Logic ---

/**
 * Finds a warrior by name.
 * @param array $warriors The list of warriors.
 * @param string $name The name to search for.
 * @return array|null The warrior array or null if not found.
 */
function findWarrior(array $warriors, string $name): ?array {
    foreach ($warriors as $warrior) {
        if ($warrior['name'] === $name) {
            return $warrior;
        }
    }
    return null;
}

/**
 * Updates or removes a warrior from the list.
 * @param array $warriors The list of warriors.
 * @param array $updatedWarrior The warrior with updated damage (or the one to be removed).
 * @return array The updated list of warriors.
 */
function updateWarrior(array $warriors, array $updatedWarrior): array {
    foreach ($warriors as $key => $warrior) {
        if ($warrior['name'] === $updatedWarrior['name']) {
            if ($updatedWarrior['damage'] >= MAX_DAMAGE) {
                // Remove the warrior if they are dead
                unset($warriors[$key]);
            } else {
                // Update the warrior's damage
                $warriors[$key] = $updatedWarrior;
            }
            return array_values($warriors); // Re-index array
        }
    }
    return $warriors;
}

// --- Request Handling (Main Controller) ---

$warriors = loadWarriors();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $name = trim($_POST['name'] ?? '');

        if (empty($name)) {
            $messages[] = ['type' => 'error', 'text' => 'Warrior name cannot be empty.'];
        } elseif (findWarrior($warriors, $name) !== null) {
            $messages[] = ['type' => 'error', 'text' => "A warrior named '{$name}' already exists!"];
        } else {
            // Create a new warrior
            $newWarrior = [
                'name' => $name,
                'damage' => 0
            ];
            $warriors[] = $newWarrior;
            saveWarriors($warriors);
            $messages[] = ['type' => 'success', 'text' => "Warrior '{$name}' has been created!"];
            // Reload the updated list
            $warriors = loadWarriors();
        }

    } elseif ($action === 'combat') {
        $attackerName = $_POST['attacker'] ?? '';
        $defenderName = $_POST['defender'] ?? '';

        if ($attackerName === $defenderName) {
            $messages[] = ['type' => 'error', 'text' => 'A warrior cannot attack themselves. Choose two different warriors.'];
        } elseif (($attacker = findWarrior($warriors, $attackerName)) === null) {
            $messages[] = ['type' => 'error', 'text' => 'The attacker was not found.'];
        } elseif (($defender = findWarrior($warriors, $defenderName)) === null) {
            $messages[] = ['type' => 'error', 'text' => 'The defender was not found.'];
        } else {
            // Combat logic: Attacker hits Defender
            $defender['damage'] += DAMAGE_PER_HIT;

            $logMessage = "{$attacker['name']} strikes {$defender['name']} for " . DAMAGE_PER_HIT . " points of damage.";

            if ($defender['damage'] >= MAX_DAMAGE) {
                // Defender is dead
                $warriors = updateWarrior($warriors, $defender);
                $messages[] = ['type' => 'death', 'text' => $logMessage . " **{$defender['name']} has fallen!** They are removed from the game."];
            } else {
                // Defender takes damage
                $warriors = updateWarrior($warriors, $defender);
                $messages[] = ['type' => 'combat', 'text' => $logMessage . " {$defender['name']}'s total damage is now {$defender['damage']} / " . MAX_DAMAGE . "."];
            }

            saveWarriors($warriors);
            // Reload the updated list
            $warriors = loadWarriors();
        }
    }
}

// --- HTML Rendering ---

// Function to generate the HTML for a status message
function renderMessages(array $messages) {
    echo '<div id="messages" class="messages-container">';
    foreach ($messages as $msg) {
        $class = 'message ';
        if ($msg['type'] === 'success') $class .= 'success';
        elseif ($msg['type'] === 'error') $class .= 'error';
        elseif ($msg['type'] === 'combat') $class .= 'combat';
        elseif ($msg['type'] === 'death') $class .= 'death';
        else $class .= 'info';

        echo "<div class='{$class}'>{$msg['text']}</div>";
    }
    echo '</div>';
}

// Get the names of active warriors for the combat dropdowns
$activeWarriorNames = array_column($warriors, 'name');
$hasEnoughWarriors = count($warriors) >= 2;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Combat Mini-Game</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        
        /* Base Styles */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f7f9fb;
            color: #1f2937;
            margin: 0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }
        .card {
            background: white;
            padding: 1.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        /* Typography */
        .title-main {
            font-size: 1.875rem; /* 3xl */
            font-weight: 700;
            text-align: center;
            margin-bottom: 2rem;
            color: #4f46e5; /* indigo-600 */
        }
        .title-section {
            font-size: 1.25rem; /* xl */
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e5e7eb;
            color: #6366f1; /* indigo-500 */
        }
        .text-center {
            text-align: center;
        }
        .font-medium {
            font-weight: 500;
        }
        .font-bold {
            font-weight: 700;
        }
        .text-lg {
            font-size: 1.125rem;
        }
        .text-sm {
            font-size: 0.875rem;
        }
        .text-gray-500 {
            color: #6b7280;
        }
        .text-gray-700 {
            color: #374151;
        }
        .text-gray-800 {
            color: #1f2937;
        }

        /* Buttons and Inputs */
        .btn-primary {
            background-color: #4f46e5;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: background-color 0.2s;
            border: none;
            cursor: pointer;
        }
        .btn-primary:hover {
            background-color: #4338ca;
        }
        .input-text, .input-select {
            flex-grow: 1;
            padding: 0.5rem;
            border: 1px solid #d1d5db; /* gray-300 */
            border-radius: 0.5rem;
        }
        .input-text:focus, .input-select:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 1px #4f46e5;
        }
        
        /* Layout */
        .flex {
            display: flex;
        }
        .flex-col {
            flex-direction: column;
        }
        .justify-between {
            justify-content: space-between;
        }
        .items-center {
            align-items: center;
        }
        .gap-4 > * {
            margin-bottom: 1rem;
        }
        .gap-4 {
            margin-bottom: -1rem; /* Adjust negative margin for gap */
        }
        .space-y-4 > * {
            margin-bottom: 1rem;
        }
        .space-y-4 {
            margin-bottom: -1rem;
        }

        /* Message Styles */
        .messages-container {
            margin-bottom: 1.5rem;
        }
        .messages-container .message:not(:last-child) {
            margin-bottom: 0.5rem;
        }
        .message {
            padding: 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        .message.info {
            background-color: #e0f2fe; /* blue-100 */
            color: #1e40af; /* blue-800 */
        }
        .message.success {
            background-color: #d1fae5; /* green-100 */
            color: #065f46; /* green-800 */
        }
        .message.error {
            background-color: #fee2e2; /* red-100 */
            color: #991b1b; /* red-800 */
        }
        .message.combat {
            background-color: #fffbeb; /* yellow-100 */
            color: #92400e; /* yellow-800 */
            border-left: 4px solid #f59e0b; /* yellow-500 */
        }
        .message.death {
            background-color: #fee2e2; /* red-200 */
            color: #7f1d1d; /* red-900 */
            border-left: 4px solid #b91c1c; /* red-700 */
            font-weight: 700;
        }

        /* Warrior List Items */
        .warrior-item {
            padding: 0.75rem;
            background-color: #f9fafb; /* gray-50 */
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
        }
        .warrior-stats-area {
            width: 100%;
        }
        .stats-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.25rem;
        }

        /* Progress Bar */
        .progress-bar-container {
            background-color: #e5e7eb;
            border-radius: 9999px;
            height: 8px;
            overflow: hidden;
        }
        .progress-bar {
            height: 100%;
            transition: width 0.3s ease-in-out;
        }
        .health-high {
            background-color: #10b981; /* green-500 */
        }
        .health-mid {
            background-color: #f97316; /* orange-500 */
        }
        .health-low {
            background-color: #ef4444; /* red-500 */
        }
        .health-critical {
            color: #b91c1c; /* red-700 */
            font-weight: 700;
        }
        .health-normal {
            color: #10b981; /* green-500 */
        }
        .damage-taken-label {
            color: #6b7280;
        }

        /* Responsive Adjustments (min-width: 640px equivalent to Tailwind 'sm') */
        @media (min-width: 640px) {
            /* Create Form */
            .flex-row-sm {
                flex-direction: row;
            }
            .gap-4-sm > * {
                margin-bottom: 0;
            }
            .gap-4-sm {
                margin-bottom: 0;
            }
            
            /* Combat Section */
            .combat-input-row {
                flex-direction: row;
            }
            .combat-input-row .input-label {
                white-space: nowrap;
            }

            /* Buttons */
            .btn-auto-sm {
                width: auto;
            }

            /* Warrior Item */
            .warrior-item {
                flex-direction: row;
                align-items: center;
            }
            .warrior-stats-area {
                width: 66.666%; /* w-2/3 */
            }
            .warrior-name {
                margin-bottom: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="title-main">Mini Jeu de Combat</h1>

        <?php renderMessages($messages); ?>

        <!-- Warrior Creation Section -->
        <div class="card">
            <h2 class="title-section">1. Create a Warrior</h2>
            <form method="POST" class="flex flex-col flex-row-sm gap-4 gap-4-sm">
                <input type="hidden" name="action" value="create">
                <input
                    type="text"
                    name="name"
                    placeholder="Enter Warrior Name (Unique)"
                    required
                    class="input-text"
                >
                <button type="submit" class="btn-primary">
                    Create Warrior
                </button>
            </form>
        </div>

        <!-- Combat Section -->
        <div class="card">
            <h2 class="title-section">2. Initiate Combat</h2>

            <?php if ($hasEnoughWarriors): ?>
                <form method="POST" class="space-y-4">
                    <input type="hidden" name="action" value="combat">
                    <div class="flex flex-col combat-input-row gap-4 items-center">
                        <label for="attacker" class="font-medium input-label">Attacker:</label>
                        <select name="attacker" id="attacker" required class="input-select">
                            <?php foreach ($activeWarriorNames as $name): ?>
                                <option value="<?= htmlspecialchars($name) ?>"><?= htmlspecialchars($name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="flex flex-col combat-input-row gap-4 items-center">
                        <label for="defender" class="font-medium input-label">Defender:</label>
                        <select name="defender" id="defender" required class="input-select">
                            <?php foreach ($activeWarriorNames as $name): ?>
                                <option value="<?= htmlspecialchars($name) ?>"><?= htmlspecialchars($name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn-primary btn-auto-sm">
                            Strike! (-<?= DAMAGE_PER_HIT ?> Damage)
                        </button>
                    </div>
                </form>
            <?php else: ?>
                <p class="text-center warrior-item">
                    You need at least two warriors to start a fight! Please create more.
                </p>
            <?php endif; ?>
        </div>

        <!-- Warrior Status Section -->
        <div class="card">
            <h2 class="title-section">3. Active Warriors (<?= count($warriors) ?>)</h2>

            <?php if (empty($warriors)): ?>
                <p class="text-center text-gray-500" style="padding: 1rem;">No warriors are currently active. Start by creating one above!</p>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($warriors as $warrior):
                        $damage_percent = min(100, ($warrior['damage'] / MAX_DAMAGE) * 100);
                        $health_class = '';
                        if ($damage_percent > 70) {
                            $health_class = 'health-low';
                        } elseif ($damage_percent > 40) {
                            $health_class = 'health-mid';
                        } else {
                            $health_class = 'health-high';
                        }
                        
                        $health_text_class = $damage_percent >= 100 ? 'health-critical' : ($damage_percent > 70 ? 'health-low' : 'health-normal');
                        // Calculate health percentage (100 - damage percentage) for display
                        $health_percent = 100 - $damage_percent;
                    ?>
                        <div class="warrior-item flex flex-col items-start warrior-item">
                            <div class="font-bold text-lg text-gray-800 warrior-name">
                                <?= htmlspecialchars($warrior['name']) ?>
                            </div>
                            <div class="warrior-stats-area">
                                <p class="text-sm font-medium stats-row">
                                    <span class="damage-taken-label">Damage Taken: <?= $warrior['damage'] ?> / <?= MAX_DAMAGE ?></span>
                                    <span class="<?= $health_text_class ?>">
                                        Health: <?= number_format($health_percent, 0) ?>%
                                    </span>
                                </p>
                                <div class="progress-bar-container">
                                    <div class="progress-bar <?= $health_class ?>" style="width: <?= number_format($health_percent, 0) ?>%;"></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
<?php
?>