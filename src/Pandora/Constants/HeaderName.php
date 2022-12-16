<?php

namespace Pandora\Constants;

enum HeaderName: string {
    case AUTHORIZATION = "Authorization";
    case CONTENT_LENGTH = "Content-Length";
    case CONTENT_TYPE = "Content-Type";
    case LOCATION = "Location";
}
