
    <div class="buttons">
<?php if (isset($_SESSION['edit-report']) && $_SESSION['edit-report'] === true) { ?> 
        <a href="#" id="submit-create-report-form">
            Save Changes
        </a>
<?php } else { ?>
        <a href="#" id="submit-create-report-form">
            Add Report
        </a>
<?php } ?>
        <a href="/dashboard">
            Cancel
        </a>
    </div>
