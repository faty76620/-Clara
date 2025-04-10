<?php
// Semaine actuelle (lundi -> dimanche)
$startOfWeek = date('Y-m-d', strtotime('monday this week'));
$endOfWeek = date('Y-m-d', strtotime('sunday this week'));

$weekSchedules = array_filter($schedules, function ($s) use ($startOfWeek, $endOfWeek) {
    $date = date('Y-m-d', strtotime($s['start_datetime']));
    return $date >= $startOfWeek && $date <= $endOfWeek;
});

$groupedByDay = [];

foreach ($weekSchedules as $schedule) {
    $day = date('l d/m', strtotime($schedule['start_datetime'])); // Ex: Monday 01/04
    $groupedByDay[$day][] = $schedule;
}
?>
<div class="week-section">
    <?php if (empty($groupedByDay)): ?>
        <p>Aucun planning prévu pour cette semaine.</p>
    <?php else: ?>
        <?php foreach ($groupedByDay as $day => $slots): ?>
            <div class="day-title"><?= htmlspecialchars($day) ?></div>
            <div class="grid-week">
                <?php foreach ($slots as $schedule): ?>
                    <div class="card-slot">
                        <h3><?= htmlspecialchars($schedule['user_firstname'] . ' ' . $schedule['user_lastname']) ?></h3>
                        <p><strong>Patient :</strong> <?= htmlspecialchars($schedule['patient_firstname'] . ' ' . $schedule['patient_lastname']) ?></p>
                        <p><strong>Heure :</strong> <?= date('H:i', strtotime($schedule['start_datetime'])) ?> - <?= date('H:i', strtotime($schedule['end_datetime'])) ?></p>
                        <p><strong>Type :</strong> <?= htmlspecialchars($schedule['care_type'] ?? 'Non spécifié') ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

