
<div class="container my-3">
    <form action="{{ route('users.sync') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">Sync Users dari API</button>
    </form>
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<a href="{{ route('user.create') }}">Tambah user disini</a>
<div class="container">
    <h1>Daftar Users dari API Eksternal</h1>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(count($users))
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user['id'] ?? '-' }}</td>
                    <td>{{ $user['name'] ?? '-' }}</td>
                    <td>{{ $user['email'] ?? '-' }}</td>
                    <td>
                        <a href="{{ route('user.edit', $user['id']) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('user.delete', $user['id']) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Tidak ada data user.</p>
    @endif
</div>

<div class="container">
    <h1>Daftar Users dari Database Lokal</h1>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(count($userbiasa))
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                @foreach($userbiasa as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user['id'] ?? '-' }}</td>
                    <td>{{ $user['name'] ?? '-' }}</td>
                    <td>{{ $user['email'] ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Tidak ada data user.</p>
    @endif
</div>