<?php
abstract class EstadosPagos
{
    const checkoutMp = 1;
    const checkoutEfectivo = 2;

    const mpApproved = 3;
    const efectivoPagado = 4;

    const mpPending = 5;
    const mpInProcess = 6;
    const mpInMediation = 7;

    const mpRejected = 8;
    const mpCancelled = 9;

    const mpRefunded = 10;
    const mpChargedBack = 11;

    const desconocido = 999;
    /*
    pending
    El usuario aún no completó el proceso de pago.
    approved
    El pago fue aprobado y acreditado.
    in_process
    El pago está siendo revisado.
    in_mediation
    Los usuarios tienen iniciada una disputa.
    rejected
    El pago fue rechazado. El usuario puede intentar pagar nuevamente.
    cancelled
    El pago fue cancelado por una de las partes, o porque el tiempo expiró.
    refunded
    El pago fue devuelto al usuario.
    charged_back
    Fue hecho un contracargo en la tarjeta del pagador.
    */
}
