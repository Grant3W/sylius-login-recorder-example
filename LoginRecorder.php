<?php

declare(strict_types=1);

namespace App\LoginRecorder;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Tracks user logins to the Sylius control panel by recording each
 * login event to the admin-logins.csv file.
 */
class LoginRecorder
{
    /**
     * Entry point method listening for the Sylius
     * 'security.interactive_login' event.
     *
     * @param InteractiveLoginEvent $event
     */
    public function recordLoginEvent(InteractiveLoginEvent $event)
    {
        $this->checkLogExists();
        $login = $this->createLoginRecord($event);
        $this->writeDataToLog('a', $login);
    }

    /**
     * Checks for an existing log file. If one cannot
     * be found, it creates an empty log with headers.
     */
    private function checkLogExists()
    {
        if(!file_exists('admin-logins.csv')) {
            $headers = [
                'Time',
                'Username',
                'IP Address',
                'User Agent',
            ];
            $this->writeDataToLog('w', $headers);
        }
    }

    /**
     * Writes data to the admin-logs.csv file. Used for the
     * initial set up and for adding subsequent logs.
     *
     * @param string $mode
     * @param array $data
     */
    private function writeDataToLog(string $mode, array $data)
    {
        $logFile = fopen('admin-logins.csv', $mode);
        fputcsv($logFile, $data);
        fclose($logFile);
    }

    /**
     * Parses the InteractiveLoginEvent to create the log for an
     * individual login event.
     *
     * @param InteractiveLoginEvent $event
     * @return array
     */
    private function createLoginRecord(InteractiveLoginEvent $event)
    {
        return [
            (new \DateTime('now'))->format('d-m-Y H:i:s'),
            $event->getAuthenticationToken()->getUser()->getUsername(),
            $event->getRequest()->getClientIp(),
            $event->getRequest()->headers->get('User-Agent'),
        ];
    }
}
