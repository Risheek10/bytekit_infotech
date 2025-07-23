<?php
// bytekit_infotech/admin/categories.php

include 'includes/admin_header.php'; // Handles session, access control, layout

$errors = [];
$success_message = '';
$editing_category = null; // To hold category data if we are in edit mode

// --- Handle Form Submissions ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_id = filter_var($_POST['category_id'] ?? null, FILTER_VALIDATE_INT);
    $name = sanitize_input($_POST['name'] ?? '');
    $description = sanitize_input($_POST['description'] ?? '');

    // Validate input
    if (empty($name)) {
        $errors[] = "Category Name is required.";
    } elseif (strlen($name) > 255) {
        $errors[] = "Category Name cannot exceed 255 characters.";
    }

    if (empty($errors)) {
        try {
            if ($category_id) {
                // UPDATE existing category
                $stmt = $pdo->prepare("UPDATE categories SET name = ?, description = ?, updated_at = CURRENT_TIMESTAMP WHERE category_id = ?");
                $stmt->execute([$name, $description, $category_id]);
                $_SESSION['message'] = "Category '" . htmlspecialchars($name) . "' updated successfully!";
            } else {
                // INSERT new category
                $stmt = $pdo->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
                $stmt->execute([$name, $description]);
                $_SESSION['message'] = "Category '" . htmlspecialchars($name) . "' added successfully!";
            }
            redirect('categories.php');
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') { // SQLSTATE for integrity constraint violation (e.g., duplicate unique key)
                $errors[] = "Error: A category with this name already exists.";
            } else {
                $errors[] = "Database error: " . htmlspecialchars($e->getMessage());
            }
            error_log("Admin Categories DB Error (Add/Edit): " . $e->getMessage());
        }
    }
}

// --- Handle Delete Action (via GET, for simplicity, but POST is generally safer) ---
// Note: A more robust solution would use a POST form for deletion as well.
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $category_id_to_delete = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

    if (!$category_id_to_delete) {
        $_SESSION['error'] = "No category ID specified for deletion.";
    } else {
        try {
            // Check if products are associated with this category
            $stmt_check_products = $pdo->prepare("SELECT COUNT(*) FROM products WHERE category_id = ?");
            $stmt_check_products->execute([$category_id_to_delete]);
            if ($stmt_check_products->fetchColumn() > 0) {
                $_SESSION['error'] = "Cannot delete category ID " . $category_id_to_delete . " as it has associated products. Please reassign products first.";
            } else {
                // Get name for message
                $stmt_name = $pdo->prepare("SELECT name FROM categories WHERE category_id = ?");
                $stmt_name->execute([$category_id_to_delete]);
                $category_name_result = $stmt_name->fetch(PDO::FETCH_ASSOC);
                $category_name = $category_name_result ? htmlspecialchars($category_name_result['name']) : 'Unknown Category';

                $stmt_delete = $pdo->prepare("DELETE FROM categories WHERE category_id = ?");
                $stmt_delete->execute([$category_id_to_delete]);

                if ($stmt_delete->rowCount() > 0) {
                    $_SESSION['message'] = "Category '" . $category_name . "' (ID: " . $category_id_to_delete . ") has been deleted successfully.";
                } else {
                    $_SESSION['error'] = "Category with ID " . $category_id_to_delete . " not found or could not be deleted.";
                }
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = "Database error during deletion: " . htmlspecialchars($e->getMessage());
            error_log("Admin Categories DB Error (Delete): " . $e->getMessage());
        }
    }
    redirect('categories.php'); // Always redirect after processing GET/POST
    exit;
}

// --- Handle Edit Mode (if 'action=edit' in URL) ---
if (isset($_GET['action']) && $_GET['action'] == 'edit') {
    $category_id_to_edit = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
    if ($category_id_to_edit) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM categories WHERE category_id = ?");
            $stmt->execute([$category_id_to_edit]);
            $editing_category = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$editing_category) {
                $_SESSION['error'] = "Category not found for editing.";
                redirect('categories.php');
                exit;
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = "Database error fetching category for edit: " . htmlspecialchars($e->getMessage());
            error_log("Admin Categories DB Error (Fetch Edit): " . $e->getMessage());
            redirect('categories.php');
            exit;
        }
    } else {
        $_SESSION['error'] = "No category ID specified for editing.";
        redirect('categories.php');
        exit;
    }
}


// --- Fetch all categories for listing ---
$categories = [];
try {
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo '<div class="error-messages"><p>Error fetching categories: ' . htmlspecialchars($e->getMessage()) . '</p></div>';
    error_log("Admin Categories Page DB Error (Fetch All): " . $e->getMessage());
}
?>

<section class="admin-content">
    <h2>Manage Categories</h2>

    <?php
    // Display session messages (from add/edit/delete actions)
    if (isset($_SESSION['message'])) {
        echo '<div class="session-message success-message"><p>' . htmlspecialchars($_SESSION['message']) . '</p></div>';
        unset($_SESSION['message']);
    }
    if (isset($_SESSION['error'])) {
        echo '<div class="session-message error-message"><p>' . htmlspecialchars($_SESSION['error']) . '</p></div>';
        unset($_SESSION['error']);
    }

    // Display form for adding/editing a category
    ?>
    <div class="category-form-section">
        <h3><?php echo $editing_category ? 'Edit Category' : 'Add New Category'; ?></h3>
        <?php
        // Display validation errors if any from POST
        if (!empty($errors)) {
            echo '<div class="error-messages">';
            foreach ($errors as $error) {
                echo '<p>' . htmlspecialchars($error) . '</p>';
            }
            echo '</div>';
        }
        ?>
        <form action="categories.php<?php echo $editing_category ? '?action=edit&id=' . htmlspecialchars($editing_category['category_id']) : ''; ?>" method="POST" class="auth-form">
            <?php if ($editing_category): ?>
                <input type="hidden" name="category_id" value="<?php echo htmlspecialchars($editing_category['category_id']); ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="name">Category Name:</label>
                <input type="text" id="name" name="name" 
                       value="<?php echo htmlspecialchars($editing_category['name'] ?? $_POST['name'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description (Optional):</label>
                <textarea id="description" name="description" rows="3"><?php echo htmlspecialchars($editing_category['description'] ?? $_POST['description'] ?? ''); ?></textarea>
            </div>
            
            <button type="submit" class="button"><?php echo $editing_category ? 'Update Category' : 'Add Category'; ?></button>
            <?php if ($editing_category): ?>
                <a href="categories.php" class="button" style="background-color:#6c757d; margin-left: 10px;">Cancel Edit</a>
            <?php endif; ?>
        </form>
    </div>

    <hr style="margin: 40px 0; border: 0; border-top: 1px dashed #eee;">

    <h3>Existing Categories</h3>
    <?php if (empty($categories)): ?>
        <p>No categories found.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($category['category_id']); ?></td>
                        <td><?php echo htmlspecialchars($category['name']); ?></td>
                        <td><?php echo htmlspecialchars($category['description'] ?: 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($category['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($category['updated_at'] ?? 'N/A'); ?></td>
                        <td class="admin-actions">
                            <a href="categories.php?action=edit&id=<?php echo htmlspecialchars($category['category_id']); ?>" class="button edit-button">Edit</a>
                            <a href="categories.php?action=delete&id=<?php echo htmlspecialchars($category['category_id']); ?>" 
                               onclick="return confirm('WARNING: Deleting a category will prevent products assigned to it from being displayed correctly if not reassigned. Are you sure you want to delete this category?');" 
                               class="button delete-button">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>

<?php
include 'includes/admin_footer.php';
?>