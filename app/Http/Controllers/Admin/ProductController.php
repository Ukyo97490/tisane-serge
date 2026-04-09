<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('categorie')) {
            $query->where('category_id', $request->categorie);
        }

        $products   = $query->orderBy('sort_order')->paginate(20)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('active', true)->orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'benefits'    => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'unit'        => 'required|string|max:50',
            'stock'       => 'required|integer|min:0',
            'image'       => 'nullable|image|max:2048',
            'active'      => 'boolean',
            'sort_order'  => 'integer|min:0',
        ]);

        $data['slug']   = Str::slug($data['name']);
        $data['active'] = $request->boolean('active', true);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Produit créé avec succès.');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('active', true)->orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'benefits'    => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'unit'        => 'required|string|max:50',
            'stock'       => 'required|integer|min:0',
            'image'       => 'nullable|image|max:2048',
            'active'      => 'boolean',
            'sort_order'  => 'integer|min:0',
        ]);

        $data['active'] = $request->boolean('active');

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Produit mis à jour.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return back()->with('success', 'Produit supprimé.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'integer|exists:products,id',
            'action' => 'required|in:activate,deactivate,delete',
        ]);

        $products = Product::whereIn('id', $request->ids);

        switch ($request->action) {
            case 'activate':
                $products->update(['active' => true]);
                return back()->with('success', count($request->ids) . ' produit(s) activé(s).');

            case 'deactivate':
                $products->update(['active' => false]);
                return back()->with('success', count($request->ids) . ' produit(s) désactivé(s).');

            case 'delete':
                foreach ($products->get() as $product) {
                    if ($product->image) {
                        Storage::disk('public')->delete($product->image);
                    }
                    $product->delete();
                }
                return back()->with('success', count($request->ids) . ' produit(s) supprimé(s).');
        }
    }

    public function downloadExample()
    {
        $categories = Category::orderBy('name')->get();

        $tmpZip = tempnam(sys_get_temp_dir(), 'exemple_import_') . '.zip';
        $zip = new ZipArchive();
        $zip->open($tmpZip, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // CSV exemple
        $csvPath = tempnam(sys_get_temp_dir(), 'exemple_csv_');
        $fh = fopen($csvPath, 'w');
        fprintf($fh, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($fh, ['name', 'slug', 'category_slug', 'description', 'benefits', 'price', 'unit', 'stock', 'active', 'sort_order', 'image_filename'], ';');

        // Lignes d'exemple basées sur les vraies catégories
        $firstSlug  = $categories->first()?->slug ?? 'tisanes';
        $secondSlug = $categories->skip(1)->first()?->slug ?? $firstSlug;

        $examples = [
            [
                'name'           => 'Tisane Verveine Réunionnaise',
                'slug'           => 'tisane-verveine-reunionnaise',
                'category_slug'  => $firstSlug,
                'description'    => 'Tisane artisanale à base de verveine cueillie à la main.',
                'benefits'       => 'Apaisante, favorise le sommeil, digestion légère.',
                'price'          => '5.50',
                'unit'           => '50g',
                'stock'          => '30',
                'active'         => '1',
                'sort_order'     => '1',
                'image_filename' => 'verveine.jpg',
            ],
            [
                'name'           => 'Miel de Letchi',
                'slug'           => 'miel-de-letchi',
                'category_slug'  => $secondSlug,
                'description'    => 'Miel artisanal récolté lors de la floraison du letchi.',
                'benefits'       => 'Riche en antioxydants, goût floral délicat.',
                'price'          => '8.00',
                'unit'           => '250g',
                'stock'          => '15',
                'active'         => '1',
                'sort_order'     => '2',
                'image_filename' => '',
            ],
            [
                'name'           => 'Sirop Gingembre',
                'slug'           => 'sirop-gingembre',
                'category_slug'  => $firstSlug,
                'description'    => 'Sirop de gingembre frais cultivé localement.',
                'benefits'       => 'Tonifiant, stimule la circulation.',
                'price'          => '6.00',
                'unit'           => '250ml',
                'stock'          => '0',
                'active'         => '0',
                'sort_order'     => '3',
                'image_filename' => '',
            ],
        ];

        foreach ($examples as $row) {
            fputcsv($fh, array_values($row), ';');
        }
        fclose($fh);

        $zip->addFile($csvPath, 'produits.csv');

        // README dans le ZIP
        $readme = $this->buildReadme($categories);
        $zip->addFromString('LISEZ-MOI.txt', $readme);

        // Dossier images (vide avec un fichier explicatif)
        $zip->addFromString('images/PLACER_LES_IMAGES_ICI.txt',
            "Placez vos images dans ce dossier.\r\n" .
            "Le nom du fichier doit correspondre exactement à la colonne image_filename du CSV.\r\n" .
            "Formats acceptés : jpg, jpeg, png, webp, gif\r\n"
        );

        $zip->close();
        unlink($csvPath);

        return response()->download($tmpZip, 'exemple_import_produits.zip', [
            'Content-Type' => 'application/zip',
        ])->deleteFileAfterSend(true);
    }

    private function buildReadme($categories): string
    {
        $lines = [];
        $lines[] = "========================================";
        $lines[] = "  EXEMPLE D'IMPORT PRODUITS - Tisane Lontan";
        $lines[] = "========================================";
        $lines[] = "";
        $lines[] = "STRUCTURE DU ZIP";
        $lines[] = "----------------";
        $lines[] = "  produits.csv        -> fichier de données (séparateur : point-virgule)";
        $lines[] = "  images/             -> dossier des images (optionnel)";
        $lines[] = "  LISEZ-MOI.txt       -> ce fichier";
        $lines[] = "";
        $lines[] = "COLONNES DU CSV";
        $lines[] = "---------------";
        $lines[] = "  name            (obligatoire) Nom du produit";
        $lines[] = "  slug            (optionnel)   Identifiant URL — généré depuis le nom si vide";
        $lines[] = "  category_slug   (obligatoire) Voir liste des catégories ci-dessous";
        $lines[] = "  description     (optionnel)   Description longue";
        $lines[] = "  benefits        (optionnel)   Bienfaits du produit";
        $lines[] = "  price           (obligatoire) Prix en euros, ex : 5.50";
        $lines[] = "  unit            (obligatoire) Unité, ex : 50g, 250ml, unité";
        $lines[] = "  stock           (obligatoire) Quantité en stock (nombre entier)";
        $lines[] = "  active          (optionnel)   1 = visible en boutique, 0 = masqué (défaut : 1)";
        $lines[] = "  sort_order      (optionnel)   Ordre d'affichage (défaut : 0)";
        $lines[] = "  image_filename  (optionnel)   Nom du fichier image dans le dossier images/";
        $lines[] = "";
        $lines[] = "CATÉGORIES DISPONIBLES";
        $lines[] = "----------------------";

        if ($categories->isEmpty()) {
            $lines[] = "  (aucune catégorie créée pour l'instant)";
        } else {
            foreach ($categories as $cat) {
                $status = $cat->active ? 'active' : 'inactive';
                $lines[] = sprintf("  %-30s  slug : %s  [%s]", $cat->name, $cat->slug, $status);
            }
        }

        $lines[] = "";
        $lines[] = "RÈGLES D'IMPORT";
        $lines[] = "---------------";
        $lines[] = "  - Si un produit avec le même slug existe déjà, il sera MIS À JOUR.";
        $lines[] = "  - Si le slug est nouveau, le produit sera CRÉÉ.";
        $lines[] = "  - Les images existantes ne sont remplacées que si une nouvelle est fournie.";
        $lines[] = "  - Les lignes avec une catégorie inconnue sont ignorées.";
        $lines[] = "";
        $lines[] = "ENCODAGE";
        $lines[] = "--------";
        $lines[] = "  Enregistrez le CSV en UTF-8 (avec ou sans BOM).";
        $lines[] = "  Séparateur de colonnes : point-virgule ( ; )";
        $lines[] = "  Ouvrir/éditer avec LibreOffice Calc ou Excel (importer en UTF-8).";

        return implode("\r\n", $lines);
    }

    public function exportZip()
    {
        $products = Product::with('category')->orderBy('sort_order')->get();

        $tmpZip = tempnam(sys_get_temp_dir(), 'export_produits_') . '.zip';
        $zip = new ZipArchive();
        $zip->open($tmpZip, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // CSV
        $csvPath = tempnam(sys_get_temp_dir(), 'produits_csv_');
        $fh = fopen($csvPath, 'w');
        fprintf($fh, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8
        fputcsv($fh, ['name', 'slug', 'category_slug', 'description', 'benefits', 'price', 'unit', 'stock', 'active', 'sort_order', 'image_filename'], ';');

        foreach ($products as $product) {
            $imageFilename = '';
            if ($product->image) {
                $imageFilename = basename($product->image);
                $fullPath = Storage::disk('public')->path($product->image);
                if (file_exists($fullPath)) {
                    $zip->addFile($fullPath, 'images/' . $imageFilename);
                }
            }
            fputcsv($fh, [
                $product->name,
                $product->slug,
                $product->category->slug ?? '',
                $product->description ?? '',
                $product->benefits ?? '',
                number_format($product->price, 2, '.', ''),
                $product->unit,
                $product->stock,
                $product->active ? '1' : '0',
                $product->sort_order,
                $imageFilename,
            ], ';');
        }
        fclose($fh);

        $zip->addFile($csvPath, 'produits.csv');
        $zip->close();

        unlink($csvPath);

        return response()->download($tmpZip, 'export_produits_' . now()->format('Y-m-d') . '.zip', [
            'Content-Type' => 'application/zip',
        ])->deleteFileAfterSend(true);
    }

    public function importZip(Request $request)
    {
        $request->validate([
            'zip_file' => 'required|file|mimes:zip|max:51200',
        ]);

        $zipFile = $request->file('zip_file');
        $zip = new ZipArchive();

        if ($zip->open($zipFile->getRealPath()) !== true) {
            return back()->with('error', 'Impossible d\'ouvrir le fichier ZIP.');
        }

        $tmpDir = sys_get_temp_dir() . '/import_produits_' . uniqid();
        mkdir($tmpDir, 0755, true);
        $zip->extractTo($tmpDir);
        $zip->close();

        $csvPath = $tmpDir . '/produits.csv';
        if (!file_exists($csvPath)) {
            $this->deleteTmpDir($tmpDir);
            return back()->with('error', 'Le fichier produits.csv est introuvable dans le ZIP.');
        }

        $fh = fopen($csvPath, 'r');
        // Retire le BOM UTF-8 si présent
        $bom = fread($fh, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($fh);
        }

        $headers = fgetcsv($fh, 0, ';');
        if (!$headers) {
            fclose($fh);
            $this->deleteTmpDir($tmpDir);
            return back()->with('error', 'Le fichier CSV est vide ou mal formaté.');
        }

        $headers = array_map('trim', $headers);
        $required = ['name', 'category_slug', 'price', 'unit', 'stock'];
        $missing = array_diff($required, $headers);
        if ($missing) {
            fclose($fh);
            $this->deleteTmpDir($tmpDir);
            return back()->with('error', 'Colonnes manquantes : ' . implode(', ', $missing));
        }

        $created = 0;
        $updated = 0;
        $errors  = [];
        $row     = 1;

        while (($line = fgetcsv($fh, 0, ';')) !== false) {
            $row++;
            if (count($line) < count($headers)) {
                continue;
            }
            $data = array_combine($headers, $line);

            // Catégorie
            $categorySlug = trim($data['category_slug'] ?? '');
            $category = Category::where('slug', $categorySlug)->first();
            if (!$category) {
                $errors[] = "Ligne $row : catégorie « $categorySlug » introuvable.";
                continue;
            }

            // Slug
            $name = trim($data['name'] ?? '');
            if (!$name) {
                $errors[] = "Ligne $row : nom vide.";
                continue;
            }
            $slug = !empty($data['slug']) ? trim($data['slug']) : Str::slug($name);

            // Image
            $imagePath = null;
            $imageFilename = trim($data['image_filename'] ?? '');
            if ($imageFilename) {
                $srcImage = $tmpDir . '/images/' . $imageFilename;
                if (file_exists($srcImage)) {
                    $destPath = 'products/' . $imageFilename;
                    Storage::disk('public')->put($destPath, file_get_contents($srcImage));
                    $imagePath = $destPath;
                }
            }

            $productData = [
                'category_id' => $category->id,
                'name'        => $name,
                'description' => $data['description'] ?? null,
                'benefits'    => $data['benefits'] ?? null,
                'price'       => (float) str_replace(',', '.', $data['price'] ?? 0),
                'unit'        => $data['unit'] ?? 'unité',
                'stock'       => (int) ($data['stock'] ?? 0),
                'active'      => isset($data['active']) ? (bool) $data['active'] : true,
                'sort_order'  => (int) ($data['sort_order'] ?? 0),
            ];

            $existing = Product::where('slug', $slug)->first();
            if ($existing) {
                if ($imagePath) {
                    if ($existing->image && $existing->image !== $imagePath) {
                        Storage::disk('public')->delete($existing->image);
                    }
                    $productData['image'] = $imagePath;
                }
                $existing->update($productData);
                $updated++;
            } else {
                $productData['slug'] = $slug;
                if ($imagePath) {
                    $productData['image'] = $imagePath;
                }
                Product::create($productData);
                $created++;
            }
        }

        fclose($fh);
        $this->deleteTmpDir($tmpDir);

        $msg = "$created produit(s) créé(s), $updated mis à jour.";
        if ($errors) {
            $msg .= ' ' . count($errors) . ' erreur(s) : ' . implode(' | ', array_slice($errors, 0, 5));
        }

        return back()->with($errors && !$created && !$updated ? 'error' : 'success', $msg);
    }

    private function deleteTmpDir(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($files as $file) {
            $file->isDir() ? rmdir($file->getRealPath()) : unlink($file->getRealPath());
        }
        rmdir($dir);
    }
}
