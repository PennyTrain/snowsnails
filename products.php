<?php
include_once "header.php";
require_once "./config/db.php";
//pagination
$limit = 12;
$page = isset($_GET["page"]) ? (int) $_GET["page"] : 1;
$page = max($page, 1);
$offset = ($page - 1) * $limit;
// removes spaces and adds %wildcards for SQL LIKE searches
$search = trim($_GET["search"] ?? "");
$searchTerm = "%" . $search . "%";
$noResults = false;
// counts how many prods matched the search
// and what is needed for the pagination
$countStmt = $conn->prepare("
    SELECT COUNT(*)
    FROM products
    WHERE name LIKE :search
       OR description LIKE :search
       OR color_name LIKE :search
       OR hex_code LIKE :search
");
$countStmt->execute([
    ":search" => $searchTerm,
]);
$totalProducts = (int) $countStmt->fetchColumn();
// this retreievs the products for the current page
// sorts them alphabetically and uses LIMIT and OFFSET for the
// pagination
$stmt = $conn->prepare("
    SELECT
        product_id,
        name,
        description,
        img_url,
        category_id,
        hex_code,
        color_name
    FROM products
    WHERE name LIKE :search
       OR description LIKE :search
       OR color_name LIKE :search
       OR hex_code LIKE :search
    ORDER BY name ASC
    LIMIT :limit OFFSET :offset
");
$stmt->bindValue(":search", $searchTerm, PDO::PARAM_STR);
$stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
$stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
// if no results show all products to not leave the user
// staring at a blank screen and recalc pagination
if (empty($products) && $search !== "") {
    $noResults = true;
    $fallbackStmt = $conn->prepare("
        SELECT
            product_id,
            name,
            description,
            img_url,
            hex_code,
            category_id,
            color_name
        FROM products
        ORDER BY name ASC
        LIMIT :limit OFFSET :offset
    ");
    $fallbackStmt->bindValue(":limit", $limit, PDO::PARAM_INT);
    $fallbackStmt->bindValue(":offset", $offset, PDO::PARAM_INT);
    $fallbackStmt->execute();
    $products = $fallbackStmt->fetchAll(PDO::FETCH_ASSOC);
    $countProducts = $conn->query("
        SELECT COUNT(*)
        FROM products
    ");
    $totalProducts = (int) $countProducts->fetchColumn();
}
// calcs how many pages are needed for pagination
$totalPages = (int) ceil($totalProducts / $limit);

// creates page urls like ?page=3 if there is enough products for
// 3 pages worth
function pageUrl(int $page, string $search): string
{
    $params = ["page" => $page];

    if ($search !== "") {
        $params["search"] = $search;
    }

    return "?" . http_build_query($params);
}
?>

<div class="service-container py-5">

    <h1 class="heading text-center mb-4">
        Products
    </h1>

    <!-- SEARCH -->
    <form method="get" class="mb-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6">
                <div class="input-group">

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Search products..."
                        value="<?= htmlspecialchars($search) ?>"
                    >

                    <button type="submit" class="btn btn-secondary">
                        Search
                    </button>

                </div>
            </div>
        </div>
    </form>

    <!-- NO RESULTS MESSAGE -->
    <?php if ($noResults): ?>
        <div class="alert alert-warning text-center mb-4">
            No products found for
            <strong>
                <?= htmlspecialchars($search) ?>
            </strong>.

            Showing all products instead.
        </div>
    <?php endif; ?>

    <!-- PRODUCTS -->
    <div class="row g-4">
        <!-- loops through projects dynamically displaying
          cards, making the "image" backgroud color the hex code 
          if no photo -->

        <?php foreach ($products as $product): ?>

            <div class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex">

                <div class="card shadow-sm w-100 h-100">

                    <!-- IMAGE -->
                    <?php if (!empty($product["img_url"])): ?>
                        <img
                            src="<?= htmlspecialchars($product["img_url"]) ?>"
                            class="card-img-top"
                            alt="<?= htmlspecialchars($product["name"]) ?>"
                            style="height: 220px; object-fit: cover;"
                        >
                    <!-- HEX COLOR -->
                    <?php elseif (!empty($product["hex_code"])): ?>
                        <div
                            style="
                                height: 220px;
                                background-color: <?= htmlspecialchars(
                                    $product["hex_code"],
                                ) ?>;
                            "
                        ></div>
                    <!-- FALLBACK -->
                    <?php else: ?>
                        <div
                            class="d-flex align-items-center justify-content-center bg-light"
                            style="height: 220px;"
                        >
                            <span class="text-muted">
                                No Image
                            </span>
                        </div>
                    <?php endif; ?>
                    <!-- BODY -->
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">
                            <?= htmlspecialchars($product["name"]) ?>
                        </h5>
<p class="card-text text-muted flex-grow-1">
    <?= htmlspecialchars(
        ($product["category_id"] == 1 ? "Nail polish: " : "") .
            $product["description"],
    ) ?>
</p>
                        <?php if (
                            empty($product["img_url"]) &&
                            !empty($product["hex_code"])
                        ): ?>
                            <small class="text-muted">
                                Color:
                                <?= htmlspecialchars(
                                    ucfirst(
                                        $product["color_name"] ?? "Unknown",
                                    ),
                                ) ?>
                            </small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <!-- PAGINATION -->
    <?php if ($totalPages > 1): ?>
        <div class="mt-5">
            <ul class="pagination justify-content-center">
                <!-- PREVIOUS -->
                <li class="page-item <?= $page <= 1 ? "disabled" : "" ?>">
                    <a
                        class="page-link"
                        href="<?= htmlspecialchars(
                            pageUrl($page - 1, $search),
                        ) ?>"
                    >
                        Previous
                    </a>
                </li>
                <!-- PAGE NUMBERS -->
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i === $page ? "active" : "" ?>">
                        <a
                            class="page-link"
                            href="<?= htmlspecialchars(pageUrl($i, $search)) ?>"
                        >
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
                <!-- NEXT -->
                <li class="page-item <?= $page >= $totalPages
                    ? "disabled"
                    : "" ?>">
                    <a
                        class="page-link"
                        href="<?= htmlspecialchars(
                            pageUrl($page + 1, $search),
                        ) ?>"
                    >
                        Next
                    </a>
                </li>
            </ul>
        </div>
    <?php endif; ?>
</div>
<?php include_once "footer.php"; ?>
