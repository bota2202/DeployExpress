@php($p = ['pendente' => 0, 'em_transito' => 50, 'entregue' => 100, 'problema' => 50][$status])
<div class="route {{ $status }}" style="--p: {{ $p }}%">
    <div class="rail"><i></i><span class="truck">@include('icons.truck')</span></div>
    <div class="stops"><span>Saiu da fábrica</span><span>Em trânsito</span><span>Entregue</span></div>
</div>
