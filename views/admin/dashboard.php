<!DOCTYPE html>
<html lang="en">
<?php 
$title = "tableau de bord";
include __DIR__ . '/../../templates/head.php'; 

$pendingRequests = $pendingRequests ?? [];
?>
<body>
    <h2>Demandes d'inscription en attente</h2>
    
    <?php if (empty($pendingRequests)): ?>
        <p>Aucune demande en attente.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Nom de l'établissement</th>
                    <th>Nom du responsable</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pendingRequests as $request): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($request['school_name']); ?></td>
                        <td><?php echo htmlspecialchars($request['admin_name']); ?></td>
                        <td><?php echo htmlspecialchars($request['email']); ?></td>
                        <td>
                            <a href="/controllers/registrationController.php?action=approve&id=<?php echo $request['id']; ?>">Approuver</a>
                            <a href="/controllers/registrationController.php?action=reject&id=<?php echo $request['id']; ?>">Rejeter</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
