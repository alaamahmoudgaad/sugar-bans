<?php
require_once 'includes/db.php';
header('Content-Type: application/json; charset=utf-8');

$action = $_GET['action'] ?? '';

try {
    if ($action === 'getAll') {
        // جلب كل المنتجات بدون تقسيم حسب level
        $stmt = $connect->query("
            SELECT 
                p.product_id, 
                p.product_name, 
                p.description, 
                p.product_price, 
                p.product_image_url,
                c.name AS category_name
            FROM products p
            JOIN categories c ON p.category_id = c.category_id
            WHERE p.is_available = 1
            ORDER BY c.category_id, p.product_name
        ");
        $allProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // نرجعهم كـ "قسم واحد" عشان يظهروا كلهم في صفحة واحدة
        echo json_encode([[
            'sectionName' => 'All Products',
            'products' => $allProducts
        ]], JSON_UNESCAPED_UNICODE);
    }
    elseif ($action === 'getByParent' && isset($_GET['id'])) {
        $parentId = (int)$_GET['id'];
        
        // جلب جميع الأبناء المباشرين
        $stmt = $connect->prepare("SELECT category_id, name FROM categories WHERE parent_id = ? ORDER BY display_order");
        $stmt->execute([$parentId]);
        $children = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // جلب المنتجات المرتبطة مباشرة بالفئة الأم
        $stmtDirect = $connect->prepare("
            SELECT product_id, product_name, description, product_price, product_image_url 
            FROM products 
            WHERE category_id = ? AND is_available = 1
        ");
        $stmtDirect->execute([$parentId]);
        $directProducts = $stmtDirect->fetchAll(PDO::FETCH_ASSOC);
        
        $result = [];
        
        // إضافة قسم "Basics" للمنتجات المرتبطة مباشرة (زي Tiramisu تحت Western)
        if (!empty($directProducts)) {
            $result[] = [
                'sectionName' => '',
                'products' => $directProducts
            ];
        }
        
        // إضافة أقسام الأبناء مع منتجاتهم
        foreach ($children as $child) {
            $stmtChild = $connect->prepare("
                SELECT product_id, product_name, description, product_price, product_image_url 
                FROM products 
                WHERE category_id = ? AND is_available = 1
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
    elseif ($action === 'getBoxes') {
        $stmt = $connect->query("
            SELECT 
                box_id, 
                box_name AS product_name, 
                box_price AS product_price, 
                box_image_url AS product_image_url, 
                description 
            FROM sugarbans_boxes 
            WHERE is_available = 1
        ");
        $boxes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([[
            'sectionName' => 'Sugarbans Boxes',
            'products' => $boxes
        ]], JSON_UNESCAPED_UNICODE);
    }
    else {
        echo json_encode(['error' => 'Invalid action. Use: getAll, getByParent?id=X, getBoxes']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>