<?php
namespace App\Http\Controllers;
use App\Models\Tela;
use Inertia\Inertia;

class TelaController extends Controller
{
    public function index() {
        return Inertia::render('Telas/Index', ['telas' => Tela::all()]);
    }
    public function create() { return Inertia::render('Telas/Create'); }
    public function edit(Tela $tela) { return Inertia::render('Telas/Edit', ['tela' => $tela]); }
}
