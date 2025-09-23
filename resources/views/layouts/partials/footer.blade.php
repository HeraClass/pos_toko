<footer class="app-footer">
  <div class="footer">
    <strong>{{ __('common.Copyright') }} &copy; {{ date('Y') }} <a href="#">{{ config('app.name') }}</a>.</strong>
    {{ __('common.all_reserved') }}
  </div>

  <style>
    .app-footer {
      background: #fff;
      padding: 1.25rem 1.5rem;
      margin-top: auto;
      border-top: 1px solid rgba(0, 0, 0, 0.05);
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 0.9rem;
      color: var(--secondary);
    }

    .app-footer a {
      color: var(--primary);
      text-decoration: none;
      transition: var(--transition);
    }

    .app-footer a:hover {
      text-decoration: underline;
    }

    @media (max-width: 768px) {
      .app-footer {
        flex-direction: column;
        gap: 0.5rem;
        text-align: center;
        padding: 1rem;
      }
    }
  </style>
</footer>