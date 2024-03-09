<x-app title='Privacy Policy'>
    <x-slot:styles>
        <link rel="stylesheet" href="{{ asset('css/privacy/privacy.css') }}">
    </x-slot>
    <x-slot:body>
        <nav>
            <div class="navParent">
                <div class="logo">
                    <a href="{{ route('user.login') }}">
                        <x-logo color="rgb(39, 135, 245)"/>
                    </a>
                </div>
            </div>
        </nav>
        <main>
            <div class="container">
                <div class="item">
                    <div class="box">
                        <h1>SpyBird - Privacy Policy</h1>
                        <h4>Effective Date: [Insert Date]</h4>
                        <h2>1. introduction</h2>
                        <h4>Welcome to SpyBird, a real-time chat WEB application (the "App") provided by Samvel Gevorgyan ("we," "us," or "our"). This Privacy Policy explains how we collect, use, and disclose information about users of the App ("you" or "users").</h4>
                        <h2>2. information we collect</h2>
                        <h3>2.1. personal information</h3>
                        <h4>We may collect certain personal information that can identify you as an individual when you use the App, such as:</h4>
                        <h4>
                            <ul>
                                <li>Your name</li>
                                <li>Your email address</li>
                                <li>Your profile picture</li>
                            </ul>
                        </h4>
                        <h3>2.2. Chat Data</h3>
                        <h4>When you use the App, we may collect and store the content of your chat messages, including text, images, and multimedia files.</h4>
                        <h3>2.3. Usage Information</h3>
                        <h4>We automatically collect certain information about how you use the App, including:</h4>
                        <h4>
                            <ul>
                                <li>Device information (e.g., IP address, browser type, device type)</li>
                                <li>Log information (e.g., access times, pages viewed, interactions)</li>
                                <li>Usage patterns and preferences</li>
                            </ul>
                        </h4>
                        <h2>3. How We Use Your Information</h2>
                        <h3>3.1. Providing and Improving the App</h3>
                        <h4>We use the collected information to:</h4>
                        <h4>
                            <ul>
                                <li>Deliver, maintain, and improve the functionality and features of the App.</li>
                                <li>Monitor and analyze usage patterns and trends to enhance the user experience.</li>
                            </ul>
                        </h4>
                        <h3>3.2. Communications</h3>
                        <h4>We may use your email address to send you important updates, announcements, and information about the App. You can opt-out of these communications at any time.</h4>
                        <h3>3.3. Legal Compliance</h3>
                        <h4>We may use your information to comply with applicable laws, regulations, or legal processes.</h4>
                        <h2>4. Information Sharing and Disclosure</h2>
                        <h4>We do not sell, trade, or otherwise transfer your personal information to third parties. However, we may share information in the following circumstances:</h4>
                        <h4>
                            <ul>
                                <li>With service providers who assist in App operations and maintenance.</li>
                                <li>In response to a legal request or to comply with the law.</li>
                            </ul>
                        </h4>
                        <h2>5. Data Security</h2>
                        <h4>We take reasonable measures to protect your personal information from unauthorized access, loss, misuse, or alteration. However, no method of transmission over the Internet or electronic storage is completely secure.</h4>
                        <h2>6. Your Choices</h2>
                        <h4>You can access, update, or delete your personal information through the App's settings. If you have any questions or requests, please contact us at <a href="mailto:{{ env('MAIL_FROM_ADDRESS') }}">{{ env('MAIL_FROM_ADDRESS') }}</a></h4>
                        <h2>7. Children's Privacy</h2>
                        <h4>The App is not intended for children under the age of 18. We do not knowingly collect personal information from children under 18. If you are a parent or guardian and believe that your child has provided us with personal information, please contact us.</h4>
                        <h2>8. Changes to this Privacy Policy</h2>
                        <h4>We may update this Privacy Policy from time to time. The updated version will be posted on this page, and the effective date will be revised accordingly.</h4>
                        <h2>9. Contact Us</h2>
                        <h4>If you have any questions or concerns about this Privacy Policy or our privacy practices, please contact us at <a href="mailto:{{ env('MAIL_FROM_ADDRESS') }}">{{ env('MAIL_FROM_ADDRESS') }}</a></h4>
                    </div>
                </div>
            </div>
        </main>
    </x-slot>
    <x-slot:scripts>
        <script src="{{ asset('js/privacy/privacy.js') }}"></script>
    </x-slot>
</x-app>