<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UsersComponent extends Component
{
    use WithPagination;
    public $showCreateUserForm, $username, $email, $password;

    public function showingCreateUserComponent()
    {
        $this->showCreateUserForm = !$this->showCreateUserForm;
    }

    // Récupérer les événements pour le formulaire
    public function mount()
    {

    }

    public function rules()
    {
        return [
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', Rules\Password::defaults()],
        ];
    }

    public function createUser()
    {
        // TODO: Implement ticket creation logic here
        // 1- Validation du formulaire
        $this->validate();

        User::create([
            'name' => $this->username,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'creator_id' => Auth::user()->id,
            'type_user' => 'controller',
        ]);


        // 3- Affichage d'un message de confirmation
        return redirect()->route('users');
    }

    public function render()
    {
        $users = User::paginate(5);
        return view('livewire.users.users-component', ['users' => $users]);
    }
}


