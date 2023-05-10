@if($patient_count)
    <div class="col-lg-2 mb-3">
        <div class="card bg-info text-white shadow">
            <div class="card-body">
                {{$department_name}}
                <div class="mt-1 text-white-50 small">
                    {{$available_doctors<=$patient_count?'Full': ($available_doctors-$patient_count). ' Place left'}}
                </div>
            </div>
        </div>
    </div>   
@else          
    <div class="col-lg-2 mb-3">
        <div class="card bg-light text-black shadow">
            <div class="card-body">
                {{$department_name}}
                <div class="mt-1 text-black-50 small">
                    {{$available_doctors}} Places Free
                </div>
            </div>
        </div>
    </div>            
@endif         