@props([
    'title' => 'Bagaimana anda menilai tutor kami ?', 
    'id' => 'ratingModal',
])

<style>
    #{{ $id }} .rating-stars {
        display: flex;
        justify-content: center;
        gap: .25rem;
        margin-block: 1rem;
    }
    #{{ $id }} .rate-star {
        background: none;
        border: 0;
        padding: .25rem .5rem;
        font-size: 2.5rem;
        line-height: 1;
        color: #adb5bd;
        transition: color .15s ease, transform .15s ease;
    }

    #{{ $id }} .rate-star:hover {
        transform: scale(1.2);
    }
    #{{ $id }} .rate-star.active {
        color: #ffc107; 
    }
    #{{ $id }} .rate-star:focus-visible {
        outline: 2px solid #ffc107;
        outline-offset: 2px;
        border-radius: .5rem; 
    }
</style>

<div class="modal fade" id="{{ $id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" id="ratingForm">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="{{ $id }}Title">{{ $title }}</h1>
                <button type="button" class="btn-close" id="ratingClose"></button>
            </div>
            <div class="modal-body">
                <div class="container text-center mb-0">
                    <img 
                        id="rTutorFoto"
                        src=".." 
                        class="rounded-circle border border-2 shadow-sm mb-2" 
                        style="width: 115px; height: 115px; object-fit: cover;"
                        alt=""
                    >
                    <p class="fw-semibold mb-0" id="rTutorName">...</p>
                </div>
                
                <div class="rating-stars">
                    @for ($i = 1; $i <= 5; $i++)
                        <button type="button" class="rate-star" data-value="{{ $i }}" aria-label="Rating {{ $i }}">
                            <i class="bi bi-star"></i>
                        </button>
                    @endfor
                </div>

                <input type="hidden" id="rValue" name="rating" value="0">

                <textarea id="rComment" class="form-control" rows="3" maxlength="500"
                        placeholder="Berikan ulasan disini ..."></textarea>
                <div class="text-danger small mt-2 d-none" id="rError"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" id="ratingSkip">Nanti saja</button>
                <button type="submit" class="btn btn-primary" id="ratingSubmit" disabled>Kirim</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function(){
        const el      = document.getElementById(`{{ $id }}`);
        const modal   = new bootstrap.Modal(el);
        const wrap    = el.querySelector('.rating-stars');
        const stars   = el.querySelectorAll('.rate-star');
        const input   = el.querySelector('#rValue');
        const ulasan = el.querySelector('#rComment');

        let ratingId = null;

        function openRating(id, tutor, foto){
            $('#rTutorFoto').attr('src', '');
            $('#rTutorFoto').attr('alt', '');
            $('#rTutorName').textContent('');
            $('#rError').addClass('d-none');
            $('#ratingSubmit').prop('disabled', true);
            render(0);
            
            $('#rTutorFoto').attr('src', foto);
            $('#rTutorFoto').attr('alt', tutor);
            $('#rTutorName').textContent(tutor);
            ratingId = id;
            modal.show();
        }

        // get Pending rating otomatis check
        if(@json((bool) auth()->user()?->student)){
            $.get('{{ route("siswa.rating.pending") }}', function (res){
                if (res.data) openRating(res.data.id, res.data.tutor_name, res.data.foto);
            });
        }

        // manual dari halaman riwayat
        // $(document).on('click', '.btn-rate', function () {
        // const d = $(this).data();
        // openRating(d.id, d.tutor, d.foto);
        // });

        const render = (n) => stars.forEach((s, i) => {
            const on = i < n;
            s.classList.toggle('active', on);
            const icon = s.querySelector('i');
            icon.classList.toggle('bi-star-fill', on);
            icon.classList.toggle('bi-star', !on);
        });

        stars.forEach((s, i) => {
            s.addEventListener('mouseenter', () => render(i + 1));
            s.addEventListener('click', () => {
                input.value = i + 1;
                $('#ratingSubmit').prop('disabled', false);
                render(input.value);
            });
        });

        wrap.addEventListener('mouseleave', () => render(input.value)); 


        //submit rating
        $('#ratingForm').on('submit', function(e){
            e.preventDefault();
            $('#ratingSubmit').prop('disabled', true);

            $.ajax({
                url: '{{ url("siswa/rating") }}/' + ratingId,
                method:'POST',
                headers:{
                    'X-CSRF-TOKEN':'{{ csrf_token() }}'
                },
                data: {
                    'score' : input.value,
                    'ulasan' : ulasan.value
                },
                success:function(res){
                    if(!res.status){
                        $('#rError').removeClass('d-none')
                            .text(res.errors.length() > 0 ? res.errors : res.message);
                    }else{
                        modal.hide();
                        showToast('Terimakasih atas penilaian anda !!', 'success');
                    }
                }, error:function(xhr){
                    console.log(xhr.responseJSON?.message);
                    showToast('terjadi kesalahan, mohon coba lagi');
                    $('#ratingSubmit').prop('disabled', false);
                }
            });
        });

        $('#ratingSkip', '#ratingClose').on('click', () => { modal.hide(); });
    });
</script>