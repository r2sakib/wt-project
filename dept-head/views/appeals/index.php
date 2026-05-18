<?php
session_start();
if(!isset($_SESSION['user_id'])) exit;
require_once __DIR__ . '/../../controllers/AppealController.php';
?>
<?php
session_start();
if(!isset($_SESSION['user_id'])) exit;
require_once __DIR__ . '/../../controllers/AppealController.php';
?>
<div class="programs-container">
    <div class="page-header">
        <div class="header-info">
            <h2>Grade Appeals Resolution Desk</h2>
            <p class="text-muted">Review student claims, evaluate faculty inputs, and issue structural revision commands.</p>
        </div>
    </div>

    <?php if(isset($_SESSION['success_msg'])): ?>
        <div style="background: #d1fae5; color: #065f46; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem;">
            <?php echo $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['error_msg'])): ?>
        <div style="background: #fee2e2; color: #991b1b; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem;">
            <?php echo $_SESSION['error_msg']; unset($_SESSION['error_msg']); ?>
        </div>
    <?php endif; ?>

    <div class="table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Student Details</th>
                    <th>Course</th>
                    <th>Statements & Case Remarks</th>
                    <th>Current Status</th>
                    <th style="width: 300px;" class="text-right">Administrative Decision Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($appeals)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 3rem; color: #777;">
                            No academic grade appeals logged for processing.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($appeals as $appeal): ?>
                        <tr style="vertical-align: top;">
                            <td>
                                <strong><?php echo htmlspecialchars($appeal['student_name']); ?></strong>
                                <br><span style="font-size:1.2rem; color:#64748b;"><?php echo htmlspecialchars($appeal['student_id_number']); ?></span>
                            </td>
                            <td class="font-medium"><?php echo htmlspecialchars($appeal['course_code']); ?></td>
                            <td>
                                <div style="margin-bottom: 0.8rem;">
                                    <strong style="font-size:1.1rem; color:#ef4444;">👨‍🎓 Student Reason:</strong>
                                    <p style="margin: 0.2rem 0; color:#334155; font-style:italic;"><?php echo htmlspecialchars($appeal['reason']); ?></p>
                                </div>
                                <div>
                                    <span style="font-size:1.1rem; font-weight:600; color:#2563eb;">👨‍🏫 Faculty Comment:</span>
                                    <p style="margin: 0.2rem 0; color:#334155;">
                                        <?php echo !empty($appeal['faculty_comment']) ? htmlspecialchars($appeal['faculty_comment']) : '<span class="text-muted">No comment logged by lecturer yet.</span>'; ?>
                                    </p>
                                </div>
                                <?php if (!empty($appeal['head_note'])): ?>
                                    <div style="margin-top: 0.8rem; padding-top: 0.5rem; border-top: 1px dashed #e2e8f0;">
                                        <strong style="font-size:1.1rem; color:#475569;">📋 Decision Instructions Logged:</strong>
                                        <p style="margin: 0.2rem 0; color:#475569; font-weight:500;"><?php echo htmlspecialchars($appeal['head_note']); ?></p>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php 
                                $status = strtolower($appeal['status']);
                                $badgeClass = 'badge-pending';
                                if ($status === 'approved') $badgeClass = 'badge-active';
                                if ($status === 'rejected') $badgeClass = 'badge-inactive';
                                ?>
                                <span class="badge <?php echo $badgeClass; ?>">
                                    <?php echo strtoupper(htmlspecialchars($appeal['status'])); ?>
                                </span>
                            </td>
                            <td class="text-right">
                                <?php if ($appeal['status'] === 'pending' || $appeal['status'] === 'under_review'): ?>
                                    <form action="controllers/AppealController.php" method="POST" style="background:#f8fafc; padding:1rem; border-radius:6px; border:1px solid #e2e8f0; text-align:left;">
                                        <input type="hidden" name="appeal_id" value="<?php echo $appeal['id']; ?>">
                                        
                                        <label style="font-size:1.1rem; font-weight:600; display:block; margin-bottom:0.4rem;">Resolution Notes / Instructions:</label>
                                        <textarea name="head_note" rows="2" placeholder="Type reason to reject OR instructions for faculty review..." required style="width:100%; font-family:inherit; font-size:1.2rem; padding:0.5rem; border-radius:4px; border:1px solid #cbd5e1; margin-bottom:0.8rem;"></textarea>
                                        
                                        <div style="display:flex; gap:0.5rem; justify-content:flex-end;">
                                            <button type="submit" name="action" value="reject" class="btn-secondary" style="background:#fee2e2; color:#991b1b; border:1px solid #fca5a5; padding:0.6rem 1.2rem; font-size:1.2rem;">❌ Reject</button>
                                            <button type="submit" name="action" value="approve" class="btn-primary" style="padding:0.6rem 1.2rem; font-size:1.2rem;">✔ Approve & Assign</button>
                                        </div>
                                    </form>
                                <?php else: ?>
                                    <span class="text-muted" style="font-weight:500;">Case Closed</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>