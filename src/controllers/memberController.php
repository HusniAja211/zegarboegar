<?php
require_once __DIR__ . '/../models/memberModels.php';
require_once __DIR__ . '/../helpers/SessionManager.php';

class MemberController
{
    private $memberModel;

    public function __construct()
    {
        $this->memberModel = new Member();
    }

    /**
     * Tampilkan daftar semua member
     */
    public function listMember()
    {
        SessionManager::start();
        if (!SessionManager::isLoggedIn()) {
            header("Location: /login?error=unauthorized");
            exit;
        }

        $allMembers = $this->memberModel->getAllMembers();

        // Pagination
        $perPage = 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $totalData = count($allMembers);
        $totalPages = ceil($totalData / $perPage);
        $start = ($page - 1) * $perPage;
        $dataPage = array_slice($allMembers, $start, $perPage);

        require __DIR__ . '/../views/member.php';
    }

    /**
     * Tampilkan form tambah member
     */
    public function tambahMember()
    {
        SessionManager::start();
        if (!SessionManager::isLoggedIn()) {
            header("Location: /login?error=unauthorized");
            exit;
        }

        require __DIR__ . '/../views/teMember.php'; 
    }

    /**
     * Simpan member baru ke database
     */
    public function storeMember()
    {
        SessionManager::start();
        if (!SessionManager::isLoggedIn()) {
            header("Location: /login?error=unauthorized");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = trim($_POST['nama_member'] ?? '');
            $telepon = trim($_POST['no_hp'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $poin = (int) ($_POST['poin'] ?? 0);

            if (empty($nama) || empty($email)) {
                header("Location: /teMember?error=empty");
                exit;
            }

            if ($this->memberModel->findByEmail($email)) {
                header("Location: /teMember?error=email_exists");
                exit;
            }

            $success = $this->memberModel->register($nama, $telepon, $email, $poin);

            if ($success) {
                header("Location: /member?success=created");
            } else {
                header("Location: /teMember?error=failed");
            }
            exit;
        }
    }

    /**
     * Menampilkan form edit member
     */
    public function editMember($id)
    {
        SessionManager::start();
        if (!SessionManager::isLoggedIn()) {
            header("Location: /login?error=unauthorized");
            exit;
        }

        $member = $this->memberModel->getMemberById($id);
        if (!$member) {
            header("Location: /member?error=notfound");
            exit;
        }

        require __DIR__ . '/../views/teMember.php';
    }

    /**
     * Update data member
     */
    public function updateMember()
    {
        SessionManager::start();
        if (!SessionManager::isLoggedIn()) {
            header("Location: /login?error=unauthorized");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_member'] ?? null;
            $nama = trim($_POST['nama_member'] ?? '');
            $telepon = trim($_POST['no_hp'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $poin = (int) ($_POST['poin'] ?? 0);

            if (empty($id) || empty($nama) || empty($email)) {
                header("Location: /member/edit/$id?error=empty");
                exit;
            }

            $success = $this->memberModel->updateMember($id, $nama, $telepon, $email, $poin);

            if ($success) {
                header("Location: /member?success=updated");
            } else {
                header("Location: /member/edit/$id?error=update_failed");
            }
            exit;
        }
    }

    /**
     * Hapus data member
     */
    public function deleteMember($id)
    {
        SessionManager::start();
        if (!SessionManager::isLoggedIn()) {
            header("Location: /login?error=unauthorized");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Method not allowed";
            exit;
        }

        $member = $this->memberModel->getMemberById($id);
        if (!$member) {
            header("Location: /member?error=notfound");
            exit;
        }

        $deleted = $this->memberModel->deleteMember($id);

        if ($deleted) {
            header("Location: /member?success=deleted");
        } else {
            header("Location: /member?error=delete_failed");
        }
        exit;
    }

    public function detailMember($id)
    {
        SessionManager::start();
        if (!SessionManager::isLoggedIn()) {
            header("Location: /login?error=unauthorized");
            exit;
        }

        $memberModel = new Member();
        $detailMember = $memberModel->getMemberById($id);

        if (!$detailMember) {
            // kalau ID tidak ada, redirect atau tampilkan 404
            header("Location: /kasir?error=notfound");
            exit;
        }

        require __DIR__ . '/../views/teMember.php';
    }

    public function getMemberByTelp()
    {
        header('Content-Type: application/json; charset=utf-8');

        $telp = $_GET['t'] ?? '';

        if (empty($telp)) {
            echo json_encode(['status' => 'error', 'message' => 'Nomor telepon tidak diberikan.']);
            return;
        }

        $member = $this->memberModel->getMemberByTelp($telp);

        if ($member) {
            echo json_encode([
                'status' => 'success',
                'nama' => $member['nama_member'],
                'poin' => (int) $member['poin'],
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Member tidak ditemukan.']);
        }
    }
}
