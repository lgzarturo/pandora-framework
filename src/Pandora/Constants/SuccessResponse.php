<?php

namespace Pandora\Constants;

enum SuccessResponse: int {
    case OK = 200;
    case CREATED = 201;
    case ACCEPTED = 202;
    case NO_CONTENT = 204;
    case REDIRECT = 302;
}
