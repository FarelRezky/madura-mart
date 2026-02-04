@extends('layout.master')

@section('menu')
    @include('layout.menu')
@endsection

@section('purchase')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root { --soft-shadow: 0 20px 27px 0 rgba(0, 0, 0, 0.05); }
        .card-modern { border: none; border-radius: 20px; box-shadow: var(--soft-shadow); background: #fff; overflow: hidden; }
        .table-modern thead th { font-size: 0.75rem; letter-spacing: 1px; text-transform: uppercase; color: #8392ab; border-bottom: 1px solid #edf2f7; padding: 1.5rem 1rem; background-color: #f8f9fa; }
        .table-modern td { padding: 1rem; vertical-align: middle; border-bottom: 1px solid #f1f1f1; color: #495057; }
        .badge-soft { padding: 6px 12px; border-radius: 8px; font-weight: 600; font-size: 0.75rem; }
        .badge-soft-primary { background-color: #e7f1ff; color: #5e72e4; }
        .badge-soft-success { background-color: #e6fffa; color: #2dce89; }
        .btn-action { background: transparent; border: none; transition: .2s; }
        .btn-action:hover { transform: translateY(-2px); }
    </style>

    <div class="container-fluid py-4" style="min-height: 85vh;">
        @if (session('success'))
            <div class="alert alert-success text-white border-0 shadow-sm mb-4">{{ session('success') }}</div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card card-modern mb-4">
                    <div class="card-header pb-0 bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0 font-weight-bolder text-dark">{{ $title }} Management</h5>
                            <p class="text-sm text-muted mb-0">Track your incoming inventory.</p>
                        </div>
                        <a href="{{ route('purchases.create') }}" class="btn bg-gradient-dark btn-sm mb-0 shadow-lg" style="border-radius: 8px;">
                            <i class="fas fa-plus me-2"></i>New Purchase
                        </a>
                    </div>

                    <div class="card-body px-0 pt-3 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table table-modern align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center opacity-7">No.</th>
                                        <th class="ps-4 opacity-7">Note Number</th>
                                        <th class="text-center opacity-7">Date</th>
                                        <th class="text-center opacity-7">Distributor</th>
                                        <th class="text-center opacity-7">Total Amount</th>
                                        <th class="text-center opacity-7">Items</th>
                                        <th class="text-center opacity-7">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($datas as $no => $item)
                                        <tr>
                                            <td class="text-center"><span class="text-secondary text-xs font-weight-bold">{{ $no + 1 }}</span></td>
                                            <td class="ps-4"><h6 class="mb-0 text-sm font-weight-bold text-dark">{{ $item->note_number }}</h6></td>
                                            <td class="text-center"><span class="text-xs font-weight-bold">{{ $item->purchase_date->format('d M Y') }}</span></td>
                                            <td class="text-center"><span class="badge badge-soft badge-soft-primary">{{ $item->distributor->name ?? 'Unknown' }}</span></td>
                                            <td class="text-center"><span class="text-sm font-weight-bold text-dark">Rp {{ number_format($item->total_price, 0, ',', '.') }}</span></td>
                                            {{-- Safe check for details count --}}
                                            <td class="text-center"><span class="badge badge-soft badge-soft-success">{{ $item->details ? $item->details->count() : 0 }} Items</span></td>
                                            <td class="text-center">
                                                <a href="{{ route('purchases.edit', $item->id) }}" class="btn-action text-dark me-2" title="Edit"><i class="fas fa-edit"></i></a>
                                                <form action="{{ route('purchases.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
                                                    @csrf @method('DELETE')
                                                    <button type="button" class="btn-action text-danger btn-delete" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function() {
                Swal.fire({
                    title: 'Delete Purchase?',
                    text: "This cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#344767',
                    confirmButtonText: 'Yes, Delete'
                }).then((result) => { if(result.isConfirmed) this.closest('form').submit(); });
            });
        });
    </script>
@endsection