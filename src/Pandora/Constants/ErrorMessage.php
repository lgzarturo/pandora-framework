<?php

namespace Pandora\Constants;

enum ErrorMessage: string {
    case INTERNAL = "Error interno del servidor";
    case RESOURCE_NOT_FOUND = "El recurso solicitado no existe!";
}
