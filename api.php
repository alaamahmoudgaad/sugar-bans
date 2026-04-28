<?php
require_once 'includes/db.php';
header('Content-Type: application/json; charset=utf-8');

$action = $_GET['action'] ?? '';

try {
    if ($action === 'getAll') {
        $stmt = $connect->query("
            SELECT 
                p.product_id, 
                p.product_name, 
                p.description, 
                p.product_price, 
                p.product_image_url,
                p.stock,
                c.name AS category_name
            FROM products p
            JOIN categories c ON p.category_id = c.category_id
            ORDER BY c.category_id, p.product_name
        ");
        $allProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($allProducts) > 0) {
            echo json_encode([[
                'sectionName' => 'All Products',
                'products' => $allProducts
            ]], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode([]);
        }
    }
    elseif ($action === 'getBoxes') {
        $stmt = $connect->query("
            SELECT 
                box_id, 
                box_name AS product_name, 
                box_price AS product_price, 
                box_image_url AS product_image_url, 
                description,
                products_included,
                stock
            FROM boxes 
        ");
        $boxes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($boxes) > 0) {
            echo json_encode([[
                'sectionName' => 'SugarBANS Boxes',
                'products' => $boxes
            ]], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode([]);
        }
    }
    elseif ($action === 'getByParent' && isset($_GET['id'])) {
        $parentId = (int)$_GET['id'];
        
        $stmt = $connect->prepare("SELECT category_id, name FROM categories WHERE parent_id = ? ORDER BY display_order");
        $stmt->execute([$parentId]);
        $children = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $stmtDirect = $connect->prepare("
            SELECT product_id, product_name, description, product_price, product_image_url, stock
            FROM products 
            WHERE category_id = ?
        ");
        $stmtDirect->execute([$parentId]);
        $directProducts = $stmtDirect->fetchAll(PDO::FETCH_ASSOC);
        
        $result = [];
        
        if (!empty($directProducts)) {
            $result[] = [
                'sectionName' => '',
                'products' => $directProducts
            ];
        }
        
        foreach ($children as $child) {
            $stmtChild = $connect->prepare("
                SELECT product_id, product_name, description, product_price, product_image_url, stock
                FROM products 
                WHERE category_id = ?
            ");
            $stmtChild->execute([$child['category_id']]);
            $childProducts = $stmtChild->fetchAll(PDO::FETCH_ASSOC);
            
            if (!empty($childProducts)) {
                $result[] = [
                    'sectionName' => $child['name'],
                    'products' => $childProducts
                ];
            }
        }
        
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    elseif ($action === 'updateStock' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        $productId = $input['product_id'] ?? 0;
        $quantity = $input['quantity'] ?? 0;
        $type = $input['type'] ?? 'product';
        
        if ($productId && $quantity > 0) {
            if ($type === 'product') {
                $stmt = $connect->prepare("UPDATE products SET stock = stock - ? WHERE product_id = ? AND stock >= ?");
                $stmt->execute([$quantity, $productId, $quantity]);
            } else {
                $stmt = $connect->prepare("UPDATE boxes SET stock = stock - ? WHERE box_id = ? AND stock >= ?");
                $stmt->execute([$quantity, $productId, $quantity]);
            }
            
            if ($stmt->rowCount() > 0) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Not enough stock']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
        }
    }
    else {
        echo json_encode(['error' => 'Invalid action. Use: getAll, getByParent?id=X, getBoxes, updateStock']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>