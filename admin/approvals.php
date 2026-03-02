<?php
session_start();

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: /Event-Management-System/auth/login.php');
    exit;
}

// Load repository
require_once __DIR__ . '/../app/Services/ApprovalService/getPendingApprovalsDetails.php';
// Get pending approvals
$get_pending_approvals = new GetPendingApprovalsDetailsService();
$pending_approvals = $get_pending_approvals->getPendingApprovalsDetails();

$total_pending = count($pending_approvals);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Event Approvals - Admin</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    <!-- External CSS -->
    <link rel="stylesheet" href="../assets/css/dashboard.css" />
    <link rel="stylesheet" href="../assets/css/admin-approvals.css" />
</head>
<body>
    <div class="container-fluid">
        
        <!-- Sidebar -->
        <?php include '../includes/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <div class="content-wrapper w-100">
                
                <!-- Back Button -->
                <div class="back-nav mb-3">
                    <a href="index.php" class="back-link">
                        <i class="bi bi-arrow-left"></i> Back to Dashboard
                    </a>
                </div>

                <!-- Page Header -->
                <div class="page-header">
                    <div class="header-content">
                        <div class="header-text">
                            <h2 class="page-title">Event Approval Requests</h2>
                            <p class="page-subtitle">Review and approve event creation requests from organizers</p>
                        </div>
                        <div class="header-badge">
                            <span class="pending-badge">
                                <?php echo $total_pending; ?> Pending
                            </span>
                        </div>
                    </div>
                </div>

                <?php if ($total_pending === 0): ?>
                <!-- No Pending Approvals -->
                <div class="no-approvals">
                    <div class="no-approvals-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <h3>All Caught Up!</h3>
                    <p>There are no pending event approval requests at this time.</p>
                    <a href="index.php" class="btn btn-primary mt-3">
                        <i class="bi bi-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
                <?php else: ?>

                <!-- Approvals Grid -->
                <div class="approvals-grid">
                    <?php foreach ($pending_approvals as $approval):
                        $event_date = date('l, F d, Y', strtotime($approval['event_date']));
                        $event_time = date('g:i A', strtotime($approval['event_time']));
                        $submitted = date('M d, Y - g:i A', strtotime($approval['submitted_at']));
                        $time_ago = time_elapsed_string($approval['submitted_at']);
                        ?>

                    <!-- Approval Card -->
                    <div class="approval-card">
                        
                        <!-- Card Header -->
                        <div class="approval-header">
                            <span class="category-badge"><?php echo htmlspecialchars($approval['category_name']); ?></span>
                            <span class="time-badge">
                                <i class="bi bi-clock"></i> <?php echo $time_ago; ?>
                            </span>
                        </div>

                        <!-- Card Body -->
                        <div class="approval-body">
                            <h4 class="approval-title"><?php echo htmlspecialchars($approval['title']); ?></h4>
                            
                            <div class="approval-meta">
                                <div class="meta-item">
                                    <i class="bi bi-person"></i>
                                    <span><?php echo htmlspecialchars($approval['organizer_name']); ?></span>
                                </div>
                                <div class="meta-item">
                                    <i class="bi bi-calendar3"></i>
                                    <span><?php echo $event_date; ?></span>
                                </div>
                                <div class="meta-item">
                                    <i class="bi bi-clock"></i>
                                    <span><?php echo $event_time; ?></span>
                                </div>
                                <div class="meta-item">
                                    <i class="bi bi-geo-alt"></i>
                                    <span><?php echo htmlspecialchars($approval['location']); ?></span>
                                </div>
                                <div class="meta-item">
                                    <i class="bi bi-people"></i>
                                    <span>Capacity: <?php echo $approval['capacity']; ?></span>
                                </div>
                            </div>

                            <div class="approval-description">
                                <?php
                                    $desc = htmlspecialchars($approval['description']);
                        echo strlen($desc) > 150 ? substr($desc, 0, 150) . '...' : $desc;
                        ?>
                            </div>

                            <div class="approval-submitted">
                                <i class="bi bi-info-circle"></i>
                                Submitted on <?php echo $submitted; ?>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="approval-footer">
                            <button class="btn btn-view" 
                                data-approval='<?php echo json_encode($approval); ?>'
                                onclick="viewDetails(this.dataset.approval)">
                                <i class="bi bi-eye"></i> View Details
                            </button>
                            <button class="btn btn-approve"
                                data-approval-id="<?php echo $approval['approval_id']; ?>"
                                data-event-id="<?php echo $approval['event_id']; ?>"
                                data-event-title="<?php echo htmlspecialchars($approval['title']); ?>"
                                onclick="approveEvent(this.dataset)">
                                <i class="bi bi-check-circle"></i> Approve
                            </button>
                            <button class="btn btn-reject"
                                data-approval-id="<?php echo $approval['approval_id']; ?>"
                                data-event-id="<?php echo $approval['event_id']; ?>"
                                data-event-title="<?php echo htmlspecialchars($approval['title']); ?>"
                                onclick="rejectEvent(this.dataset)">
                                <i class="bi bi-x-circle"></i> Reject
                            </button>
                        </div>

                    </div>
                    <?php endforeach; ?>
                </div>

                <?php endif; ?>

            </div>
        </div>

    </div>

    <!-- View Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Event Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="detailsContent">
                    <!-- Content populated by JS -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-approve" id="approveFromModal">
                        <i class="bi bi-check-circle"></i> Approve Event
                    </button>
                    <button type="button" class="btn btn-reject" id="rejectFromModal">
                        <i class="bi bi-x-circle"></i> Reject Event
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Rejection Reason Modal -->
    <div class="modal fade" id="rejectReasonModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-x-circle text-danger"></i> Reject Event
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Please provide a reason for rejecting "<strong id="rejectEventTitle"></strong>":</p>
                    <div class="mb-3">
                        <label for="rejectionReason" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" 
                                  id="rejectionReason" 
                                  rows="4" 
                                  placeholder="Enter reason for rejection (e.g., Does not meet guidelines, Insufficient information, etc.)"
                                  required></textarea>
                        <div class="form-text">This will be visible to the organizer.</div>
                        <div id="reasonError" class="text-danger mt-2" style="display: none;">
                            Please provide a reason for rejection.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmRejectBtn">
                        <i class="bi bi-x-circle"></i> Confirm Rejection
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    <script>
        // View full details
        function viewDetails(approvalJson) {
            const approval = JSON.parse(approvalJson);
            const eventDate = new Date(approval.event_date).toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            const eventTime = new Date('2000-01-01 ' + approval.event_time).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
            const submitted = new Date(approval.submitted_at).toLocaleString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit' });

            const content = `
                <div class="details-section">
                    <div class="details-header">
                        <span class="category-badge">${approval.category_name}</span>
                        <h3>${approval.title}</h3>
                    </div>

                    <div class="details-grid">
                        <div class="detail-item">
                            <div class="detail-label"><i class="bi bi-person"></i> Organizer</div>
                            <div class="detail-value">${approval.organizer_name}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label"><i class="bi bi-calendar3"></i> Date</div>
                            <div class="detail-value">${eventDate}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label"><i class="bi bi-clock"></i> Time</div>
                            <div class="detail-value">${eventTime}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label"><i class="bi bi-geo-alt"></i> Location</div>
                            <div class="detail-value">${approval.location}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label"><i class="bi bi-people"></i> Capacity</div>
                            <div class="detail-value">${approval.capacity} attendees</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label"><i class="bi bi-calendar-check"></i> Submitted</div>
                            <div class="detail-value">${submitted}</div>
                        </div>
                    </div>

                    <div class="description-section">
                        <h5><i class="bi bi-file-text"></i> Event Description</h5>
                        <p>${approval.description}</p>
                    </div>
                </div>
            `;

            document.getElementById('detailsContent').innerHTML = content;

            // Set data for modal buttons
            document.getElementById('approveFromModal').onclick = function() {
                bootstrap.Modal.getInstance(document.getElementById('detailsModal')).hide();
                approveEvent({ approvalId: approval.approval_id, eventId: approval.event_id, eventTitle: approval.title });
            };

            document.getElementById('rejectFromModal').onclick = function() {
                bootstrap.Modal.getInstance(document.getElementById('detailsModal')).hide();
                rejectEvent({ approvalId: approval.approval_id, eventId: approval.event_id, eventTitle: approval.title });
            };

            new bootstrap.Modal(document.getElementById('detailsModal')).show();
        }

        // Approve event
        function approveEvent(data) {
            showModal('confirm', 'Approve Event', 
                `Are you sure you want to approve "${data.eventTitle}"? This event will be published and visible to all users.`, {
                confirmText: 'Yes, Approve',
                cancelText: 'Cancel',
                onConfirm: function() {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/Event-Management-System/admin/form-handlers/approveEventHandler.php';

                    const approvalIdInput = document.createElement('input');
                    approvalIdInput.type = 'hidden';
                    approvalIdInput.name = 'approval_id';
                    approvalIdInput.value = data.approvalId;
                    form.appendChild(approvalIdInput);

                    const eventIdInput = document.createElement('input');
                    eventIdInput.type = 'hidden';
                    eventIdInput.name = 'event_id';
                    eventIdInput.value = data.eventId;
                    form.appendChild(eventIdInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Reject event
        function rejectEvent(data) {
            // Set event title in modal
            document.getElementById('rejectEventTitle').textContent = data.eventTitle;
            
            // Clear previous reason and error
            document.getElementById('rejectionReason').value = '';
            document.getElementById('reasonError').style.display = 'none';
            
            // Show rejection reason modal
            const rejectModal = new bootstrap.Modal(document.getElementById('rejectReasonModal'));
            rejectModal.show();
            
            // Handle confirm button click
            document.getElementById('confirmRejectBtn').onclick = function() {
                const reason = document.getElementById('rejectionReason').value.trim();
                
                // Validate reason
                if (!reason) {
                    document.getElementById('reasonError').style.display = 'block';
                    return;
                }
                
                // Hide error if shown
                document.getElementById('reasonError').style.display = 'none';
                
                // Close the modal
                rejectModal.hide();
                
                // Submit the form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/Event-Management-System/admin/form-handlers/rejectEventHandler.php';

                const approvalIdInput = document.createElement('input');
                approvalIdInput.type = 'hidden';
                approvalIdInput.name = 'approval_id';
                approvalIdInput.value = data.approvalId;
                form.appendChild(approvalIdInput);

                const eventIdInput = document.createElement('input');
                eventIdInput.type = 'hidden';
                eventIdInput.name = 'event_id';
                eventIdInput.value = data.eventId;
                form.appendChild(eventIdInput);
                
                const reasonInput = document.createElement('input');
                reasonInput.type = 'hidden';
                reasonInput.name = 'rejection_reason';
                reasonInput.value = reason;
                form.appendChild(reasonInput);

                document.body.appendChild(form);
                form.submit();
            };
        }
    </script>

    <!-- Include Modal -->
    <?php include '../includes/modal.php'; ?>

</body>
</html>

<?php
// Helper function
function time_elapsed_string($datetime)
{
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    if ($diff->d >= 1) {
        return $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
    }
    if ($diff->h >= 1) {
        return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
    }
    if ($diff->i >= 1) {
        return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
    }
    return 'Just now';
}
?>