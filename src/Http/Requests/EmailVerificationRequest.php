<?php
/**
 * Playground
 */
namespace Playground\Login\Blade\Http\Requests;

use Illuminate\Foundation\Auth\EmailVerificationRequest as BaseRequest;

/**
 * \Playground\Login\Blade\Http\Requests\EmailVerificationRequest
 */
class EmailVerificationRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();

        $id = $this->route('id');
        $hash = $this->route('hash');

        $userId = $user ? $user->getAttributeValue('id') : null;
        $userEmail = $user ? $user->getAttributeValue('email') : null;

        if (! is_scalar($id)
            || ! $id
            || ! is_scalar($userId)
            || ! $userId
            || ! hash_equals(strval($id), strval($userId))
        ) {
            return false;
        }

        if (! is_string($hash)
            || ! $hash
            || ! is_string($userEmail)
            || ! $userEmail
            || ! hash_equals($hash, sha1($userEmail))
        ) {
            return false;
        }

        return true;
    }
}
