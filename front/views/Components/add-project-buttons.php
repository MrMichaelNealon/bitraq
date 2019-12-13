
    <div class="buttons">
<?php if (isset($_SESSION['project-edit']) && $_SESSION['project-edit'] === true) { ?> 
        <a href="#" id="submit-create-project-form">
            Save Changes
        </a>
<?php } else { ?>
        <a href="#" id="submit-create-project-form">
            Add Project
        </a>
<?php } ?>
        <a href="/dashboard">
            Cancel
        </a>
    </div>
