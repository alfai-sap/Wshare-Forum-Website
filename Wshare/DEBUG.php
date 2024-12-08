<?php 
if (is_writable('community_thumbs/')) {
    echo "The comm directory is writable.";
} else {
    echo "The comm directory is NOT writable.";
}