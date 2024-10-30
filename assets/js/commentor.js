jQuery(document).ready(function($){

    $(document).on('click', '.commentor .input-box button', function() {
        let messageTextarea = $('.commentor .input-box textarea');

        addNewComment({
            'message': messageTextarea.val()
        }, function(result) {
            if (result !== false) {
                commentorShowNotice(result.data.message);
                if (result.success) {
                    messageTextarea.val('');
                }
            }
        });
    });

    $(document).on('emoji-click', '.commentor .input-box emoji-picker', function(event) {
        const { unicode } = event.detail;
        const commentInput = $('.commentor .input-box textarea');
        commentInput.val(commentInput.val() + unicode).focus();
    });

    $(document).on('click', '.commentor .input-box .emoji-selector', function() {
        $('.commentor .input-box .emoji-container').toggleClass('hidden');
    });

    $(document).on('click', '.commentor .comments comment .load-replies', function() {
        let commentId = $(this).attr('data-comment-id');
        $('comment[data-id="' + commentId + '"] .replies').removeClass('hidden');
        $(this).remove();
    });

    $(document).on('click', '.commentor .reply-box', function() {
        $('.reply-box').removeClass('focused');
        $(this).closest('.reply-box').addClass('focused');
    });

    $(document).on('click', function(e) {
        const isInsideReplyBox = $('.commentor .reply-box').is(e.target) || $('.commentor .reply-box').has(e.target).length > 0;
        if (! isInsideReplyBox) {
            $('.commentor .reply-box').removeClass('focused');
        }
    });

    $(document).on('emoji-click', '.commentor .reply-box emoji-picker', function(event) {
        let commentInput = $(this).closest('.reply-box').find('input');
        commentInput.val(commentInput.val() + event.detail.unicode).focus();
    });

    $(document).on('click', '.commentor .reply-box .emoji-selector', function() {
        const emojiContainer = $(this).closest('.reply-box').find('.emoji-container');
        emojiContainer.toggleClass('hidden');
    });

    $(document).on('click', '.commentor .reply-box .send', function() {
        let element = $(this);
        element.addClass('disabled');
        let message = element.closest('.reply-box').find('input');
        let replyTo = element.closest('comment').attr('data-id');

        addNewComment({
            'message': message.val(),
            'reply_to': replyTo
        }, function(result) {

            if (result !== false) {
                commentorShowNotice(result.data.message);

                if (result.success) {
                    message.val('');
                }
            }
        });
    });

    function addNewComment(data, callback) {

        let isUserLoggedIn = $('.commentor').attr('data-is-user-logged-in');

        if (isUserLoggedIn !== 'true') {
            if (! localStorage.getItem('commentor_user_name') || ! localStorage.getItem('commentor_user_email')) {
                $('.commentor .guest-popup-container').removeClass('hidden');
                return false;
            } else {
                data.name = localStorage.getItem('commentor_user_name');
                data.email = localStorage.getItem('commentor_user_email');
            }
        }

        data.action = 'commentor_create_comment';
        data.wp_nonce = ajax_data.nonce;
        data.post_id = $('input#postId').val();

        $.ajax({
            type: 'POST',
            url: ajax_data.ajax_url,
            data: data,
            success: function (response) {
                if (response.success) {
                    callback(response);
                } else {
                    callback(response);
                }
            },
            error: function (xhr, status, error) {
                callback(false);
            }
        });
    }


    $(document).on('click', '.commentor comment .likes', function() {
        const likeButton = $(this);

        if (likeButton.hasClass('disabled')) {
            return false;
        }

        likeButton.addClass('disabled');

        let commentId = likeButton.attr('data-comment-id');

        let request_data = {
            action: 'commentor_like_comment',
            wp_nonce: ajax_data.nonce,
            comment_id: commentId
        };

        $.ajax({
            type: 'POST',
            url: ajax_data.ajax_url,
            data: request_data,
            success: function (response) {
                if (response.success) {
                    likeButton.html(response.data.current_total);
                }
                commentorShowNotice(response.data.message);
                likeButton.removeClass('disabled');
            },
            error: function (xhr, status, error) {
                likeButton.removeClass('disabled')
            }
        });
    });

    function commentorShowNotice(message) {
        $('.commentor-notice').html(message);
        $('.commentor-notice').removeClass('hidden');

        setTimeout(function() {
            $('.commentor-notice').addClass('hidden');
        }, 3000);
    }

    $(document).on('click', '.commentor .guest-popup-container .guest-popup button', function() {
        const nameErrorMessage = $('.commentor .guest-popup-container .guest-popup .name').attr('data-error-message');
        const emailErrorMessage = $('.commentor .guest-popup-container .guest-popup .email').attr('data-error-message');
        let name = $('.commentor .guest-popup-container .guest-popup .name input');
        let email = $('.commentor .guest-popup-container .guest-popup .email input');

        if (name.val() === '') {
            commentorShowNotice(nameErrorMessage);
            return false;
        }

        if (email.val() === '') {
            commentorShowNotice(emailErrorMessage);
            return false;
        }

        localStorage.setItem('commentor_user_name', name.val());
        localStorage.setItem('commentor_user_email', email.val());

        $('.commentor .guest-popup-container').addClass('hidden');
        name.val('');
        email.val('');
    });

    let currentCommentsPage = 1;

    $(document).on('click', '.commentor .load-more-comments button', function() {

        let loadMoreButton = $(this);

        loadMoreButton.find('.icon').removeClass('hidden');

        let data = {
            action: 'commentor_load_comments',
            wp_nonce: ajax_data.nonce,
            post_id: $('input#postId').val(),
            page: currentCommentsPage + 1
        };

        $.post(ajax_data.ajax_url, data, function(response) {
            if (response.success) {
                currentCommentsPage++;

                loadMoreButton.find('.icon').addClass('hidden');

                $('.commentor .comments').append(response.data.html);

                if (! response.data.has_more) {
                    loadMoreButton.remove();
                }
            }
        });

    });
});