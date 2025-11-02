<?php
// app/Policies/EducationPolicy.php
namespace App\Policies;

use App\Models\Education;
use App\Models\User; // Ganti Pengguna dengan User
use Illuminate\Auth\Access\HandlesAuthorization;

class EducationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if user can create education content
     */
    public function create(User $user)
    {
        return $user->isAdmin(); // Gunakan method helper dari model User
    }

    /**
     * Determine if user can update education content
     */
    public function update(User $user, Education $education)
    {
        return $user->isAdmin(); // Gunakan method helper dari model User
    }

    /**
     * Determine if user can delete education content
     */
    public function delete(User $user, Education $education)
    {
        return $user->isAdmin(); // Gunakan method helper dari model User
    }

    /**
     * Determine if user can view statistics
     */
    public function viewStats(User $user)
    {
        return $user->isAdmin(); // Gunakan method helper dari model User
    }

    /**
     * Determine if user can view any education content
     */
    public function viewAny(User $user)
    {
        // Semua user yang aktif bisa melihat daftar education content
        return $user->isActive();
    }

    /**
     * Determine if user can view specific education content
     */
    public function view(User $user, Education $education)
    {
        // Semua user yang aktif bisa melihat detail education content
        return $user->isActive();
    }
}