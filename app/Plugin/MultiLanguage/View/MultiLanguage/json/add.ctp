<?php

/**
 * Status message for adding favorites via JSON.
 *
 */
echo json_encode(compact('message', 'status', 'type', 'foreignKey'));
