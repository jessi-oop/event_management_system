<?php
// modal.php - Reusable Modal Component
// Handles: success, error, warning, confirmation (yes/no)
// Supports: JS function calls + PHP session flash messages

// --- PHP Flash Message Handler ---
// Read and clear session flash if set
$_flash = null;
if (!empty($_SESSION['flash'])) {
    $_flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
}
?>

<!-- =============================================
     REUSABLE MODAL COMPONENT
     ============================================= -->
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content feedback-modal">

      <!-- Header -->
      <div class="modal-header feedback-modal-header" id="feedbackModalHeader">
        <div class="feedback-icon-wrap" id="feedbackIconWrap">
          <i class="bi" id="feedbackIcon"></i>
        </div>
        <h5 class="modal-title feedback-modal-title" id="feedbackModalLabel"></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Body -->
      <div class="modal-body feedback-modal-body">
        <p id="feedbackMessage" class="feedback-message"></p>
      </div>

      <!-- Footer -->
      <div class="modal-footer feedback-modal-footer" id="feedbackModalFooter">
        <!-- Dynamically populated by JS -->
      </div>

    </div>
  </div>
</div>

<!-- =============================================
     MODAL STYLES
     ============================================= -->
<style>
  .feedback-modal {
    border: none;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
  }

  .feedback-modal-header {
    padding: 1.5rem 1.5rem 1rem;
    border-bottom: none;
    display: flex;
    align-items: center;
    gap: 0.875rem;
  }

  /* Header color per type */
  .feedback-modal-header.type-success { background-color: #f0fdf4; }
  .feedback-modal-header.type-error   { background-color: #fef2f2; }
  .feedback-modal-header.type-warning { background-color: #fffbeb; }
  .feedback-modal-header.type-confirm { background-color: #eff6ff; }

  /* Icon wrap */
  .feedback-icon-wrap {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }

  .feedback-icon-wrap.type-success { background-color: #dcfce7; }
  .feedback-icon-wrap.type-error   { background-color: #fee2e2; }
  .feedback-icon-wrap.type-warning { background-color: #fef3c7; }
  .feedback-icon-wrap.type-confirm { background-color: #dbeafe; }

  .feedback-icon-wrap i {
    font-size: 1.4rem;
  }

  .feedback-icon-wrap.type-success i { color: #16a34a; }
  .feedback-icon-wrap.type-error   i { color: #dc2626; }
  .feedback-icon-wrap.type-warning i { color: #d97706; }
  .feedback-icon-wrap.type-confirm i { color: #2563eb; }

  /* Title color per type */
  .feedback-modal-title {
    font-weight: 700;
    font-size: 1.1rem;
    margin: 0;
  }

  .type-success .feedback-modal-title { color: #15803d; }
  .type-error   .feedback-modal-title { color: #b91c1c; }
  .type-warning .feedback-modal-title { color: #b45309; }
  .type-confirm .feedback-modal-title { color: #1d4ed8; }

  /* Close button color override per type */
  .type-success .btn-close,
  .type-error   .btn-close,
  .type-warning .btn-close,
  .type-confirm .btn-close {
    filter: none;
    opacity: 0.5;
  }

  /* Body */
  .feedback-modal-body {
    padding: 1.25rem 1.5rem;
  }

  .feedback-message {
    margin: 0;
    color: #374151;
    font-size: 0.95rem;
    line-height: 1.6;
  }

  /* Footer */
  .feedback-modal-footer {
    padding: 0.75rem 1.5rem 1.25rem;
    border-top: none;
    gap: 0.5rem;
  }
</style>

<!-- =============================================
     MODAL JAVASCRIPT
     ============================================= -->
<script>
  // Config per modal type
  const _modalConfig = {
    success: {
      icon: 'bi-check-circle-fill',
      headerClass: 'type-success',
      iconClass: 'type-success',
    },
    error: {
      icon: 'bi-x-circle-fill',
      headerClass: 'type-error',
      iconClass: 'type-error',
    },
    warning: {
      icon: 'bi-exclamation-triangle-fill',
      headerClass: 'type-warning',
      iconClass: 'type-warning',
    },
    confirm: {
      icon: 'bi-question-circle-fill',
      headerClass: 'type-confirm',
      iconClass: 'type-confirm',
    },
  };

  /**
   * Show a feedback modal.
   *
   * @param {string} type        - 'success' | 'error' | 'warning' | 'confirm'
   * @param {string} title       - Modal heading
   * @param {string} message     - Body message
   * @param {object} [options]   - Optional extra config
   *   @param {string}   options.confirmText   - Confirm button label (confirm type only, default: 'Yes, Confirm')
   *   @param {string}   options.cancelText    - Cancel button label (confirm type only, default: 'Cancel')
   *   @param {Function} options.onConfirm     - Callback when confirm is clicked
   *   @param {Function} options.onCancel      - Callback when cancel is clicked
   *   @param {string}   options.redirectUrl   - Redirect after dismissing (success/error/warning)
   */
  function showModal(type, title, message, options = {}) {
    const config = _modalConfig[type];
    if (!config) return console.error('showModal: unknown type "' + type + '"');

    // Set header
    const header = document.getElementById('feedbackModalHeader');
    header.className = 'modal-header feedback-modal-header ' + config.headerClass;

    // Set icon
    const iconWrap = document.getElementById('feedbackIconWrap');
    iconWrap.className = 'feedback-icon-wrap ' + config.iconClass;
    const icon = document.getElementById('feedbackIcon');
    icon.className = 'bi ' + config.icon;

    // Set title & message
    document.getElementById('feedbackModalLabel').textContent = title;
    document.getElementById('feedbackMessage').textContent = message;

    // Build footer buttons
    const footer = document.getElementById('feedbackModalFooter');
    footer.innerHTML = '';

    if (type === 'confirm') {
      const cancelBtn = document.createElement('button');
      cancelBtn.type = 'button';
      cancelBtn.className = 'btn btn-secondary';
      cancelBtn.textContent = options.cancelText || 'Cancel';
      cancelBtn.setAttribute('data-bs-dismiss', 'modal');
      if (options.onCancel) cancelBtn.addEventListener('click', options.onCancel);

      const confirmBtn = document.createElement('button');
      confirmBtn.type = 'button';
      confirmBtn.className = 'btn btn-primary';
      confirmBtn.textContent = options.confirmText || 'Yes, Confirm';
      confirmBtn.addEventListener('click', () => {
        bootstrap.Modal.getInstance(document.getElementById('feedbackModal')).hide();
        if (options.onConfirm) options.onConfirm();
      });

      footer.appendChild(cancelBtn);
      footer.appendChild(confirmBtn);
    } else {
      const closeBtn = document.createElement('button');
      closeBtn.type = 'button';
      closeBtn.className = 'btn btn-secondary';
      closeBtn.textContent = 'Close';
      closeBtn.setAttribute('data-bs-dismiss', 'modal');

      if (options.redirectUrl) {
        closeBtn.addEventListener('click', () => {
          window.location.href = options.redirectUrl;
        });
      }

      footer.appendChild(closeBtn);
    }

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('feedbackModal'));
    modal.show();
  }

  // --- Auto-trigger PHP flash messages on page load ---
  <?php if ($_flash): ?>
  document.addEventListener('DOMContentLoaded', () => {
    showModal(
      <?php echo json_encode($_flash['type']); ?>,
      <?php echo json_encode($_flash['title']); ?>,
      <?php echo json_encode($_flash['message']); ?>,
      <?php echo json_encode($_flash['options'] ?? []); ?>
    );
  });
  <?php endif; ?>
</script>