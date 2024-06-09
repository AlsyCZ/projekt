<?php
$XP_VALUES = [
    'comment' => 0.5,
    'add_discussion' => 1
];

function add_xp_for_comment($pdo, $user_id, $XP_VALUES) {
    $current_xp = get_current_xp($pdo, $user_id);
    $new_xp = $current_xp + $XP_VALUES['comment'];
    update_xp($pdo, $user_id, $new_xp);
}

function add_xp_for_discussion($pdo, $user_id, $XP_VALUES) {
    $current_xp = get_current_xp($pdo, $user_id);
    $new_xp = $current_xp + $XP_VALUES['add_discussion'];
    update_xp($pdo, $user_id, $new_xp);
}

function get_current_xp($pdo, $user_id) {
    $sql = "SELECT xp FROM uzivatele WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    $current_xp = $stmt->fetchColumn();
    $stmt->closeCursor();
    
    if ($current_xp === false) {
        $current_xp = 1;
    }
    
    return $current_xp;
}

function update_xp($pdo, $user_id, $new_xp) {
    $sql = "UPDATE uzivatele SET xp = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$new_xp, $user_id]);
}

function update_user_role($pdo, $user_id, $role) {
    $sql = "UPDATE uzivatele SET role = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$role, $user_id]);
    $_SESSION['role'] = $role;
}
?>

