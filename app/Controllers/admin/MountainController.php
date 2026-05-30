<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GunungModel; 
use App\Models\MountainRoutesModel;

class MountainController extends BaseController
{
    protected $mountainModel;
    protected $routeModel;

    public function __construct()
    {
        $this->mountainModel = new GunungModel(); 
        $this->routeModel = new MountainRoutesModel();
    }

    public function index()
    {
        return view('admin/gunung/index', [
            'daftar_gunung' => $this->mountainModel->orderBy('NAMA_GUNUNG', 'ASC')->findAll(), 
        ]);
    }

    public function create()
    {
        return view('admin/gunung/create');
    }

    public function store()
    {
        $this->mountainModel->insert([
            'NAMA_GUNUNG'   => $this->request->getPost('nama_gunung'),
            'LOKASI'        => $this->request->getPost('lokasi'),
            'STATUS_JALUR'  => $this->request->getPost('status_jalur') ?? 'Buka',
            'KAPASITAS_MAX' => (int) $this->request->getPost('kapasitas_max'), 
        ]);

        return redirect()->to(base_url('admin/gunung'))->with('success', 'Gunung berhasil ditambahkan.');
    }

    public function edit($id)
    {
        return view('admin/gunung/edit', [
            'gunung' => $this->mountainModel->find($id),
        ]);
    }

    public function update($id)
    {
        // 🛠️ FIX: Mengubah KUOTA_HARIAN menjadi KAPASITAS_MAX agar sinkron dengan Database & View edit.php
        $this->mountainModel->update($id, [
            'NAMA_GUNUNG'   => $this->request->getPost('nama_gunung'),
            'LOKASI'        => $this->request->getPost('lokasi'),
            'STATUS_JALUR'  => $this->request->getPost('status_jalur') ?? 'Buka',
            'KAPASITAS_MAX' => (int) $this->request->getPost('kapasitas_max'), 
        ]);

        return redirect()->to(base_url('admin/gunung'))->with('success', 'Data gunung diperbarui.');
    }

    public function delete($id)
    {
        $this->mountainModel->delete($id);
        return redirect()->to(base_url('admin/gunung'))->with('success', 'Gunung berhasil dihapus.');
    }

    public function routes($mountainId)
    {
        return view('admin/gunung/routes', [
            'gunung' => $this->mountainModel->find($mountainId),
            'routes' => $this->routeModel->where('mountain_id', $mountainId)->orderBy('route_name', 'ASC')->findAll(),
        ]);
    }

    public function routeCreate($mountainId)
    {
        return view('admin/gunung/route_create', [
            'gunung' => $this->mountainModel->find($mountainId),
        ]);
    }

    public function routeStore($mountainId)
    {
        $this->routeModel->insert([
            'mountain_id' => $mountainId,
            'route_name'  => $this->request->getPost('route_name'),
            'difficulty'  => $this->request->getPost('difficulty'),
            'distance_km' => (float) $this->request->getPost('distance_km'),
            'duration'    => $this->request->getPost('duration'),
            'status_open' => $this->request->getPost('status_open') ?? 'open',
        ]);

        return redirect()->to(base_url('admin/gunung/' . $mountainId . '/routes'))->with('success', 'Rute gunung berhasil ditambahkan.');
    }

    public function routeEdit($mountainId, $routeId)
    {
        return view('admin/gunung/route_edit', [
            'gunung' => $this->mountainModel->find($mountainId),
            'route'  => $this->routeModel->find($routeId),
        ]);
    }

    public function routeUpdate($mountainId, $routeId)
    {
        $this->routeModel->update($routeId, [
            'route_name'  => $this->request->getPost('route_name'),
            'difficulty'  => $this->request->getPost('difficulty'),
            'distance_km' => (float) $this->request->getPost('distance_km'),
            'duration'    => $this->request->getPost('duration'),
            'status_open' => $this->request->getPost('status_open') ?? 'open',
        ]);

        return redirect()->to(base_url('admin/gunung/' . $mountainId . '/routes'))->with('success', 'Rute gunung berhasil diperbarui.');
    }

    public function routeDelete($mountainId, $routeId)
    {
        $this->routeModel->delete($routeId);
        return redirect()->to(base_url('admin/gunung/' . $mountainId . '/routes'))->with('success', 'Rute gunung berhasil dihapus.');
    }

    public function toggleStatus()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request'])->setStatusCode(400);
        }

        $payload = $this->request->getJSON(true);

        if (empty($payload['id']) || !isset($payload['status'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing payload'])->setStatusCode(422);
        }

        $status_real = in_array($payload['status'], ['open', 'Buka']) ? 'Buka' : 'Tutup';

        $updated = $this->mountainModel->update((int) $payload['id'], [
            'STATUS_JALUR' => $status_real,
        ]);

        return $this->response->setJSON(['success' => (bool) $updated]);
    }
}