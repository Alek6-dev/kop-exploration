"use client";

import React, { ReactNode } from 'react';
import language from '@/messages/fr';
import { Button } from '../ui/button';

export interface ShareButtonProps {
    championship: String,
    creator: String,
    code: String
}

const ShareButton = ({ ...props }: ShareButtonProps ) => {
    const share_description = language.championship.invitation.section.share_code.button.data_share.description.label
    .replace("{championship_name}", props.championship.toString())
    .replace("{creator_pseudo}", props.creator.toString())
    .replace("{invitation_code}", props.code.toString())

    function shareClick(e: any) {
        const button = e.target;
        const dataTitle = button.getAttribute('data-share-title');
        const dataDesc = button.getAttribute('data-share-desc');
        const dataUrl = button.getAttribute('data-share-url');
        if (navigator.share) {
          navigator.share({
              title: dataTitle,
              text: dataDesc,
              url: dataUrl,
            })
            .then(() => console.log('Successful share'))
            .catch((error) => console.log('Error sharing', error));
        } else {
          console.log('Share not supported on this browser.');
        }
    }

    return (
        <Button
            variant="secondary"
            size="sm"
            className="text-sm flex-initial w-auto"
            id="share-button"
            onClick={shareClick}
            data-share-title={language.championship.invitation.section.share_code.button.data_share.title.label}
            data-share-desc={share_description}
            data-share-url={process.env.NEXT_PUBLIC_URL}>
            {language.championship.invitation.section.share_code.button.label}
        </Button>
    )
}

export { ShareButton };
