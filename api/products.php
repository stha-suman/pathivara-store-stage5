<?php
require_once __DIR__ . '/common.php';
$p = db();
$m = $_SERVER['REQUEST_METHOD'];
if ($m === 'GET') {
    $rows = $p->query("SELECT * FROM products WHERE active=1 ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as &$r) {
        $r['id'] = (int) $r['id'];
        $r['price'] = (int) $r['price'];
        $r['stock'] = (int) $r['stock'];
        $r['sizes'] = json_decode($r['sizes'], true) ?: [];
        $r['colors'] = json_decode($r['colors'], true) ?: [];
    }
    json_response($rows);
}
protect();
if ($m === 'POST') {
    $d = request_json();
    $n = clean((string) ($d['name'] ?? ''), 120);
    $c = clean((string) ($d['category'] ?? ''), 30);
    if (!$n || !in_array($c, ['Men', 'Women', 'Kids', 'Sarees'], true))
        json_response(['error' => 'Invalid product'], 422);
    $q = $p->prepare("INSERT INTO products(name,category,price,stock,image,description,sizes,colors,created_at,updated_at)VALUES(?,?,?,?,?,?,?,?,?,?)");
    $q->execute([$n, $c, max(0, (int) ($d['price'] ?? 0)), max(0, (int) ($d['stock'] ?? 0)), clean((string) ($d['image'] ?? ''), 500), clean((string) ($d['description'] ?? ''), 3000), json_encode($d['sizes'] ?? ['M', 'L', 'XL']), json_encode($d['colors'] ?? ['Default']), date('c'), date('c')]);
    json_response(['id' => $p->lastInsertId()], 201);
}
if ($m === 'PUT') {
    $id = (int) ($_GET['id'] ?? 0);
    $d = request_json();
    $q = $p->prepare("UPDATE products SET name=?,category=?,price=?,stock=?,image=?,description=?,sizes=?,colors=?,updated_at=? WHERE id=?");
    $q->execute([clean((string) ($d['name'] ?? ''), 120), clean((string) ($d['category'] ?? ''), 30), max(0, (int) ($d['price'] ?? 0)), max(0, (int) ($d['stock'] ?? 0)), clean((string) ($d['image'] ?? ''), 500), clean((string) ($d['description'] ?? ''), 3000), json_encode($d['sizes'] ?? []), json_encode($d['colors'] ?? []), date('c'), $id]);
    json_response(['ok' => true]);
}
if ($m === 'DELETE') {
    $q = $p->prepare("UPDATE products SET active=0,updated_at=? WHERE id=?");
    $q->execute([date('c'), (int) ($_GET['id'] ?? 0)]);
    json_response(['ok' => true]);
}
json_response(['error' => 'Method not allowed'], 405);
