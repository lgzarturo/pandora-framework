<?php

namespace Pandora\Constants;

enum ServerValue: string {
    case HTTP_USER_AGENT = "HTTP_USER_AGENT";
    case REMOTE_ADDR = "REMOTE_ADDR";
    case REQUEST_URI = "REQUEST_URI";
    case REQUEST_METHOD = "REQUEST_METHOD";
}
