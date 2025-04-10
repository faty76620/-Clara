<div class="grid-day">
    <?php if (empty($daySchedules)): ?>
        <p>Aucun planning prévu pour aujourd'hui.</p>
    <?php else: ?>
        <?php foreach ($daySchedules as $schedule): ?>
            <div class="card-slot">
                <h3><?= htmlspecialchars($schedule['user_firstname']) ?> <?= htmlspecialchars($schedule['user_lastname']) ?></h3>
                <p><strong>Patient :</strong> <?= htmlspecialchars($schedule['patient_firstname']) ?> <?= htmlspecialchars($schedule['patient_lastname']) ?></p>
                <p><strong>Heure :</strong> <?= date('H:i', strtotime($schedule['start_datetime'])) ?> - <?= date('H:i', strtotime($schedule['end_datetime'])) ?></p>
                <p><strong>Type de soin :</strong> <?= htmlspecialchars($schedule['care_type'] ?? 'Non spécifié') ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>